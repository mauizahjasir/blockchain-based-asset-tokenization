<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Helpers\StringHelper;
use App\Models\Asset;
use App\Models\AssetsRequest;
use App\Models\ClientAsset;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AssetsRequestController extends Controller
{
    public function index()
    {
        $assetsRequests = AssetsRequest::all();

        return view('admin/assets-request', compact('assetsRequests'));
    }

    public function requestPurchase(Request $request)
    {
        $request->validate(['asset' => 'required', 'commit_amount' => 'required']);

        /** @var User $user */
        $user = $request->user();
        $amount = (int)$request->get('commit_amount', 0);
        $asset = $request->get('asset');

        $isValidAddress = MultichainService::isValidAddress($user?->wallet_address);

        if (!$isValidAddress) {
            return redirect()->back()->with('errors', [MessageHelper::notAuthorizedUser()]);
        }

        if ($user->assetsRequests->where('asset', $asset)->isNotEmpty()) {
            return redirect()->back()->with('errors', ["You have already requested the purchase of this Asset, please wait for administrator's response"]);
        }

        if ($amount > $user->walletBalance(false)) {
            return redirect()->back()->with('errors', ['Your committed amount exceeds your wallet balance']);
        }

        $response = MultichainService::lockAssetAmount($user->wallet_address, config('multichain.currency'), $amount);


        if (empty($response)) {
            return redirect()->back()->with('errors', [MessageHelper::submissionFailure()]);
        }

        AssetsRequest::create([
            'asset' => $asset,
            'requestor_id' => $user->id,
            'additional_info' => $request->input('additional_info', null),
            'status' => AssetsRequest::OPEN,
            'request_payload' => $response,
            'commit_amount'  => $amount
        ]);

        return redirect()->route('bank-assets')->with('success', MessageHelper::submissionSuccess());
    }

    public function requestApprove(AssetsRequest $assetRequest, Request $request)
    {
        /** @var MultichainService $multichain */
        $multichain = app('multichainService');

        $isValidPerson = $multichain->isValidAddress($assetRequest->requestor->wallet_address);
        $hasPermission = $multichain->hasPermissions(['receive'], $assetRequest->requestor->wallet_address);

        if (!$isValidPerson) {
            return redirect()->back()->with('errors', [MessageHelper::notAuthorizedUser()]);
        }

        if (!$hasPermission) {
            return redirect()->back()->with('errors', [MessageHelper::doesNotHavePermission()]);
        }

        $txPayload = $assetRequest->request_payload;

        $txHex = $multichain->multichain()->createrawexchange($txPayload['txid'], $txPayload['vout'], [$assetRequest->assets->alias => $assetRequest->assets->quantity]);
        $decodedHex = $multichain->multichain()->decoderawexchange($txHex);

        if (!$decodedHex['cancomplete']) {
            return redirect()->back()->with('errors', [MessageHelper::transactionFailure()]);
        }
        $lockedHex = $multichain->multichain()->preparelockunspentfrom($request->user()->wallet_address, [$assetRequest->assets->alias => $assetRequest->assets->quantity]);
        $appendedTx = $multichain->multichain()->appendrawexchange($txHex, $lockedHex['txid'], $lockedHex['vout'], [config('multichain.currency') => $assetRequest->commit_amount]);
        $txId = $multichain->multichain()->sendrawtransaction($appendedTx['hex']);

        if ($txId === null) {
            return redirect()->back()->with('errors', [MessageHelper::transactionFailure()]);
        }

        $transaction = Transaction::create([
            'tx_hex' => $txId
        ]);

        ClientAsset::create([
            'asset_id' => $assetRequest->assets->id,
            'owner_id' => $assetRequest->requestor->id,
            'previous_owner_id' => '', // owner_id from assets table
            'tx_id' => $transaction->id
        ]);

        $assetRequest->assets->status = Asset::STATUS_SOLD;
        $assetRequest->assets->save();

        $assetRequest->status = AssetsRequest::RESOLVED;
        $assetRequest->save();


        Session::flash('success', 'Transaction has been successful');

        return redirect()->route('asset-requests');

        // createrawsendfrom
        // signrawtransaction
        // sendrawtransaction
    }

    public function requestPurchasePage(Request $request)
    {
        $asset = $request->get('asset');

        return view('client.request-purchase-form', compact('asset'));
    }

    public function requestDetails(AssetsRequest $assetRequest)
    {
        return view('admin.request-detail-form', compact('assetRequest'));
    }

    public function requestReject(AssetsRequest $assetRequest)
    {

    }
}
