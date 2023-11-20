<?php

namespace App\Http\Controllers;


use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Models\AssetsOnSale;
use Illuminate\Http\Request;

class AssetOnSaleController extends Controller
{
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

        $id = AssetsOnSale::where('asset', $request->get('asset'))->get()->first()?->id;

        if (empty($id)) {
            return redirect()->back()->with('errors', MessageHelper::submissionFailure());
        }

        AssetsOnSale::destroy($id);

        return redirect()->back()->with('success', 'Status changed successfully');
    }

    public function assetsOnSalePage(Request $request)
    {
        $user = $request->user();
        $assets = AssetsOnSale::where('owner_id', '!=', $user->id)->get()->all();

        foreach ($assets as &$asset) {
            $assetDetails = MultichainService::assetInfo($asset->asset);
            $asset->setAttribute('info', $assetDetails);
        }

        return view('client.assets-on-sale', compact('assets'));
    }
}
