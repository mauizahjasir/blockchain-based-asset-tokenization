<?php

namespace App\Http\Controllers;

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
            return redirect()->back()->with('errors', [StringHelper::errorMessage()]);
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
}
