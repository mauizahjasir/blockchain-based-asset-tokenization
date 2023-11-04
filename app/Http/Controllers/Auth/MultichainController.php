<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Services\MultichainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MultichainController extends Controller
{
    public function getInfo()
    {
        /** @var MultichainService $multichainService */
        $multichainService = app('multichainService');
        $information = $multichainService->getInfo();

        return view('admin.get-info', ['data' => $information]);
    }

    public function createAssetForm()
    {
        return view('admin.create-asset');
    }

    public function createAsset(Request $request)
    {
        // Validate the input
        $request->validate([
            'asset_name' => 'required|string',
            'asset_quantity' => 'required|integer',
            'asset_unit' => 'required|string',
        ]);

        $assetName = $request->input('asset_name');
        $assetQuantity = $request->input('asset_quantity');
        $assetUnit = $request->input('asset_unit');

        $asset = Asset::create([
            'name' => $request->input('asset_name'),
            'quantity' => $request->input('asset_quantity'),
            'unit' => $request->input('asset_unit')
        ]);

        if ($asset !== null) {
            /** @var MultichainService $multichainClient */
            $multichainClient = app('multichainService');

            $validAddress = $multichainClient->getAddressWithPermission('issue')['address'] ?? '';

            $multichainClient->issueAsset($validAddress, $assetName, $assetQuantity, $assetUnit);

            $asset->creator_wallet_address = $validAddress;
            $asset->save();
        }

        Session::flash('success', 'Asset created successfully');

        return redirect()->route('create-asset');
    }
}

