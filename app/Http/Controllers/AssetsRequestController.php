<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Models\AssetsOnSale;
use App\Models\AssetsRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AssetsRequestController extends Controller
{
    public function index()
    {
        $assetsRequest = AssetsRequest::all();

        return view('admin/assets-request', compact('assetsRequest'));
    }

    public function requestPurchase(AssetsOnSale $assetOnSale, Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->assetsRequests->where('asset', $assetOnSale->asset)->isNotEmpty()) {
            return redirect()->back()->with('errors', ["You have already requested the purchase of this Asset, please wait for administrator's response"]);
        }

        $assetInfo = MultichainService::assetInfo($assetOnSale->asset);
        $assetValue = $assetInfo['units'] * $assetInfo['issueqty'];

        if ($assetValue > $user->walletBalance(false)) {
            return redirect()->back()->with('errors', ['You do not have enough balance to purchase this asset']);
        }

        AssetsRequest::create([
            'asset' => $assetOnSale->asset,
            'requestor_id' => $user->id,
            'owner_id' => $assetOnSale->owner_id,
            'status' => AssetsRequest::AWAITING_OWNER_APPROVAL,
            'commit_amount' => $assetValue
        ]);

        return redirect()->back()->with('success', MessageHelper::submissionSuccess());
    }

    public function requestApprove(AssetsRequest $assetRequest, Request $request)
    {
        $isValidPerson = MultichainService::isValidAddress($assetRequest->requestor->wallet_address);
        $hasPermission = MultichainService::hasPermissions(['receive'], $assetRequest->requestor->wallet_address);
        $user = $request->user();

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

        $lockedHex = MultichainService::multichain()->preparelockunspentfrom($user->wallet_address, [$asset['name'] => $asset['issueqty']]);
        $appendedTx = MultichainService::multichain()->appendrawexchange($txHex, $lockedHex['txid'], $lockedHex['vout'], [config('multichain.currency') => $assetRequest->commit_amount]);
        $txId = MultichainService::multichain()->sendrawtransaction($appendedTx['hex']);

        if ($txId === null) {
            return redirect()->back()->with('errors', [MessageHelper::transactionFailure()]);
        }

        MultichainService::sendAssetFrom($user->wallet_address, $assetRequest->owner->wallet_address, config('multichain.currency'), (float)$assetRequest->commit_amount);

        Transaction::create([
            'tx_hex' => $txId
        ]);

        AssetsOnSale::where('asset', $assetRequest->asset)
            ->delete();

        $assetRequest->status = AssetsRequest::RESOLVED;
        $assetRequest->save();

        return redirect()->route('asset-requests')->with('success', 'Transaction has been successful');
    }

    public function requestDetails(AssetsRequest $assetRequest, Request $request)
    {
        $user = $request->user();

        $assetTransferred = collect(MultichainService::getAddressBalances($user->wallet_address))->where('name', $assetRequest->asset)->isNotEmpty();

        return view('admin.request-detail-form', compact('assetRequest', 'assetTransferred'));
    }

    public function requestReject(AssetsRequest $assetRequest)
    {

    }
}
