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

    public function requestDetails(AssetsRequest $assetRequest, Request $request)
    {
        $user = $request->user();

        $assetTransferred = collect(MultichainService::getAddressBalances($user->wallet_address))->where('name', $assetRequest->asset)->isNotEmpty();

        return view('admin.request-detail-form', compact('assetRequest', 'assetTransferred'));
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
}
