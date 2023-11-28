<?php

namespace App\Http\Controllers;


use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Models\AssetsOnSale;
use App\Models\AssetsRequest;
use Illuminate\Http\Request;

class AssetOnSaleController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $assets = AssetsOnSale::where('owner_id', '!=', $user->id)->where('status', AssetsOnSale::OPEN)->get()
            ->reject(function (AssetsOnSale $assetsOnSale) {
                return AssetsRequest::where('asset', $assetsOnSale->asset)->where('status', '=', AssetsRequest::AWAITING_OWNER_APPROVAL)
                    ->get()
                    ->isEmpty();
            });

        foreach ($assets as &$asset) {
            $assetDetails = MultichainService::assetInfo($asset->asset);
            $asset->setAttribute('info', $assetDetails);
        }

        return view('client.assets-on-sale', compact('assets'));
    }

    public function putOnSale(Request $request)
    {
        $request->validate([
            'asset' => 'required'
        ]);

        $user = $request->user();
        $asset = $request->get('asset');

        if (!MultichainService::isValidAddress($user->wallet_address)) {
            return redirect()->back()->with('errors', MessageHelper::notAuthorizedUser());
        }

        $usersAssets = collect(MultichainService::getAddressBalances($user->wallet_address))->pluck('name')->unique()->all();

        if (!in_array($asset, $usersAssets)) {
            return redirect()->back()->with('errors', MessageHelper::doesNotHavePermission());
        }

        AssetsOnSale::create([
            'asset' => $request->get('asset'),
            'owner_id' => $user->id
        ]);

        return redirect()->back()->with('success', 'Status changed successfully');
    }

    public function removeFromSale(Request $request)
    {
        $request->validate([
            'asset' => 'required'
        ]);

        $asset = $request->get('asset');
        $assetRequests = AssetsRequest::where('asset', $asset)->whereNotIn('status', [AssetsRequest::RESOLVED, AssetsRequest::REJECTED])->get()->count();

        if ($assetRequests > 0) {
            return redirect()->back()->with('errors', "You cannot remove this asset from sale as it has $assetRequests request(s) in pending");
        }

        $id = AssetsOnSale::where('asset', $asset)->get()->first()?->id;

        if (empty($id)) {
            return redirect()->back()->with('errors', MessageHelper::submissionFailure());
        }

        AssetsOnSale::destroy($id);

        return redirect()->back()->with('success', 'Status changed successfully');
    }
}
