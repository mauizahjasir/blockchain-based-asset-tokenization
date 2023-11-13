<?php

namespace App\Http\Controllers;

use App\Helpers\MessageHelper;
use App\Helpers\StringHelper;
use App\Models\Asset;
use App\Models\AssetsRequest;
use App\Services\MultichainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AssetsRequestController extends Controller
{
    public function index()
    {
        $assetsRequest = AssetsRequest::with('assets', 'requestor')->get();

        return view('admin/assets-request', compact('assetsRequest'));
    }

    public function requestPurchase(Asset $asset, Request $request)
    {
        $request->validate(['asset_id' => 'required']);

        $user = $request->user();

        /** @var MultichainService $multichain */
        $multichain = app('multichainService');
        $isValidAddress = $multichain->isValidAddress($user?->wallet_address);

        if (!$isValidAddress) {
            return redirect()->back()->with('errors', [MessageHelper::submissionFailure()]);
        }

        if ($user->assetsRequests->where('asset_id', $asset->id)->isNotEmpty()) {
            return redirect()->back()->with('errors', ["You have already requested the purchase of this Asset, please wait for administrator's response"]);
        }

        $asset->status = Asset::STATUS_REQUESTED;
        $asset->save();

        AssetsRequest::create([
            'asset_id' => $asset->id,
            'requestor_id' => $user->id,
        ]);

        Session::flash('success', 'Request submitted successfully');

        return redirect()->route('client.assets');
    }

    public function requestApprove(AssetsRequest $assetRequest, Request $request)
    {
        /** @var MultichainService $multichain */
        $multichain = app('multichainService');

        $isValidPerson = $multichain->isValidAddress($assetRequest->requestor->wallet_address);
        $hasPermission = $multichain->hasPermission(['receive'], $assetRequest->requestor->wallet_address);

        if (!$isValidPerson) {
            return redirect()->back()->with('errors', [MessageHelper::notAuthorizedUser()]);
        }

        if (!$hasPermission) {
            return redirect()->back()->with('errors', [MessageHelper::doesNotHavePermission()]);
        }

        // 177eEo7orJzX2Zy2SBBcXsPFzDy1z3eKGxiwfd

        // createrawsendfrom
        // signrawtransaction
        // sendrawtransaction
    }
}
