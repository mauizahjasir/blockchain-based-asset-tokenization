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
            'commit_amount' => $amount
        ]);

        return redirect()->route('bank-assets')->with('success', MessageHelper::submissionSuccess());
    }

    public function requestApprove(AssetsRequest $assetRequest, Request $request)
    {
        $isValidPerson = MultichainService::isValidAddress($assetRequest->requestor->wallet_address);
        $hasPermission = MultichainService::hasPermissions(['receive'], $assetRequest->requestor->wallet_address);

        if (!$isValidPerson) {
            return redirect()->back()->with('errors', [MessageHelper::notAuthorizedUser()]);
        }

        if (!$hasPermission) {
            return redirect()->back()->with('errors', [MessageHelper::doesNotHavePermission()]);
        }

        $txPayload = $assetRequest->request_payload;
        $asset = MultichainService::assetInfo($assetRequest->asset);

        $txHex = MultichainService::multichain()->createrawexchange($txPayload['txid'], $txPayload['vout'], [$asset['name'] => $asset['issueqty']]);
        $decodedHex = MultichainService::multichain()->decoderawexchange($txHex);

        if (empty($decodedHex) || !$decodedHex['cancomplete']) {
            return redirect()->back()->with('errors', [MessageHelper::transactionFailure()]);
        }

        $lockedHex = MultichainService::multichain()->preparelockunspentfrom($request->user()->wallet_address, [$asset['name'] => $asset['issueqty']]);
        $appendedTx = MultichainService::multichain()->appendrawexchange($txHex, $lockedHex['txid'], $lockedHex['vout'], [config('multichain.currency') => $assetRequest->commit_amount]);
        $txId = MultichainService::multichain()->sendrawtransaction($appendedTx['hex']);

        if ($txId === null) {
            return redirect()->back()->with('errors', [MessageHelper::transactionFailure()]);
        }

        Transaction::create([
            'tx_hex' => $txId
        ]);

        $assetRequest->status = AssetsRequest::RESOLVED;
        $assetRequest->save();

        return redirect()->route('asset-requests')->with('success', 'Transaction has been successful');
    }

    public function requestDetails(AssetsRequest $assetRequest)
    {
        return view('admin.request-detail-form', compact('assetRequest'));
    }

    public function requestReject(AssetsRequest $assetRequest)
    {

    }
}
