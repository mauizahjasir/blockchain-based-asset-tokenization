<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use App\Services\MultichainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AssetController extends Controller
{
    public function index()
    {
        $assetTypes = AssetType::all();

        return view('admin.create-asset', compact('assetTypes'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|integer',
            'unit' => 'required|string',
            'asset_type_id' => 'string',
        ]);

        $name = $request->input('name', '');
        $quantity = $request->input('quantity', 0);
        $unit = $request->input('unit', 0);
        $type = $request->input('asset_type_id');
        $details = $request->input('details', []);

        /** @var MultichainService $multichainClient */
        $multichainClient = app('multichainService');

        $validAddress = $multichainClient->getAddressWithPermission('issue')['address'] ?? '';
        $customFields = array_merge($details, ['type' => AssetType::find($type)?->name]);

        $txId = $multichainClient->issueAsset($validAddress, $name, $quantity, $unit, $customFields);

        if ($txId === null) {
            return redirect()->back()->with('error', 'There was an error with your submission. Please check the form and try again.');
        }

        Asset::create([
            'name' => $name,
            'quantity' => $quantity,
            'unit' => $unit,
            'asset_type_id' => $type,
            'details' => $details,
            'creator_wallet_address' => $validAddress,
            'tx_id' => $txId,
        ]);

        Session::flash('success', 'Asset created successfully');

        return redirect()->route('create-asset');
    }
}
