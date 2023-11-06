<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\MultichainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AssetController extends Controller
{
    public function index()
    {
        return view('admin.create-asset');
    }

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|integer',
            'unit' => 'required|string',
        ]);

        $assetName = $request->input('name');
        $assetQuantity = $request->input('quantity');
        $assetUnit = $request->input('unit');

        $asset = Asset::create([
            'name' => $assetName,
            'quantity' => $assetQuantity,
            'unit' => $assetUnit
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
