<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Models\AssetsOnSale;
use App\Models\AssetsRequest;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AssetsRequestController extends Controller
{
    public function index()
    {
        $assetsRequest = AssetsRequest::whereNotIn('status', [AssetsRequest::RESOLVED, AssetsRequest::REJECTED])->get();

        return view('admin/assets-request', compact('assetsRequest'));
    }

    public function historicalData()
    {
        $assetsRequest = AssetsRequest::whereIn('status', [AssetsRequest::RESOLVED, AssetsRequest::REJECTED])->get();

        return view('admin/assets-request-history', compact('assetsRequest'));
    }

    public function requestPurchase(AssetsOnSale $assetOnSale, Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->canSubmitPurchaseRequest($assetOnSale->asset)) {
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
        if (in_array($assetRequest->status, [AssetsRequest::REJECTED_BY_BUYER, AssetsRequest::REJECTED_BY_OWNER])) {
            return redirect()->back()->with('errors', 'The request has been rejected by collaborating parties');
        }

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

        return redirect()->route('asset-requests')->with([
            'success' => 'Transaction has been successful',
            'data' => "Transaction Hash: $txId"
        ]);
    }

    public function adminRequestApprove(AssetsRequest $assetRequest, Request $request)
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

        Transaction::create([
            'tx_hex' => $txId
        ]);

        $assetRequest->status = AssetsRequest::RESOLVED;
        $assetRequest->save();

        $remainingRequests = AssetsRequest::where('owner_id', $user->id)->whereIn('status', [AssetsRequest::AWAITING_ADMINS_APPROVAL])->get();

        foreach ($remainingRequests as $remReq) {
            MultichainService::multichain()->lockunspent(true, [$remReq->request_payload]);

            $remReq->status = AssetsRequest::REJECTED;
            $remReq->save();
        }

        return redirect()->route('my-requests')->with([
            'success' => 'Transaction has been successful',
            'data' => "Transaction Hash: $txId",
        ]);
    }

    public function requestDetails(AssetsRequest $assetRequest, Request $request)
    {
        $user = $request->user();

        $assetTransferred = collect(MultichainService::getAddressBalances($user->wallet_address))->where('name', $assetRequest->asset)->isNotEmpty();

        return view('admin.request-detail-form', compact('assetRequest', 'assetTransferred'));
    }

    public function adminRequestsDetails(AssetsRequest $assetRequest, Request $request)
    {
        $user = $request->user();

        $assetTransferred = collect(MultichainService::getAddressBalances($user->wallet_address))->where('name', $assetRequest->asset)->isNotEmpty();

        return view('admin.admin-request-detail-form', compact('assetRequest', 'assetTransferred'));
    }

    public function requestReject(AssetsRequest $assetRequest, Request $request)
    {
        $assetInfo = MultichainService::assetInfo($assetRequest->asset);
        MultichainService::sendAssetFrom($request->user()->wallet_address, $assetRequest->owner->wallet_address, $assetRequest->asset, (int)$assetInfo['issueqty']);

        MultichainService::multichain()->lockunspent(true, [$assetRequest->request_payload]);

        $assetRequest->status = AssetsRequest::REJECTED;
        $assetRequest->save();

        return redirect()->back()->with('success', 'Request disapproved');
    }

    public function adminRequestReject(AssetsRequest $assetRequest, Request $request)
    {
        MultichainService::multichain()->lockunspent(true, [$assetRequest->request_payload]);

        $assetRequest->status = AssetsRequest::REJECTED;
        $assetRequest->save();

        return redirect()->back()->with('success', 'Request disapproved');
    }

    public function bankAssetPurchase(Request $request)
    {
        $request->validate(['asset' => 'required']);
        $asset = $request->input('asset');

        /** @var User $user */
        $user = $request->user();

        if ($user->canSubmitPurchaseRequest($asset)) {
            return redirect()->back()->with('errors', ["You have already requested the purchase of this Asset, please wait for administrator's response"]);
        }

        $assetInfo = MultichainService::assetInfo($asset);
        $assetValue = $assetInfo['units'] * $assetInfo['issueqty'];

        if ($assetValue > $user->walletBalance(false)) {
            return redirect()->back()->with('errors', ['You do not have enough balance to purchase this asset']);
        }

        if (!MultichainService::hasPermissions(['send'], $user->wallet_address)) {
            return redirect()->back()->with('errors', MessageHelper::doesNotHavePermission());
        }

        $response = MultichainService::lockAssetAmount($user->wallet_address, config('multichain.currency'), $assetValue);

        if ($response === null) {
            return redirect()->back()->with('errors', MessageHelper::submissionFailure());
        }

        AssetsRequest::create([
            'asset' => $asset,
            'requestor_id' => $user->id,
            'owner_id' => User::adminId(),
            'status' => AssetsRequest::AWAITING_ADMINS_APPROVAL,
            'commit_amount' => $assetValue,
            'request_payload' => $response
        ]);

        return redirect()->back()->with('success', MessageHelper::submissionSuccess());
    }

    public function adminRequests(Request $request)
    {
        $assetsRequest = AssetsRequest::whereNotIn('status', [AssetsRequest::RESOLVED, AssetsRequest::REJECTED])->whereIn('owner_id', [$request->user()->id])->get();

        return view('admin/my-requests', compact('assetsRequest'));
    }
}
