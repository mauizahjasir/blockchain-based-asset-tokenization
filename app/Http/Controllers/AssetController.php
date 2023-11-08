<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\Asset;
use App\Models\AssetType;
use App\Services\MultichainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::all();

        return view('admin.assets', compact('assets'));
    }

    public function createAssetForm()
    {
        $assetTypes = AssetType::all();

        return view('admin.create-asset', compact('assetTypes'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                Rule::unique('assets', 'name'),
            ],
            'quantity' => 'required|integer',
            'unit' => 'required|string',
            'asset_type_id' => 'string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors()->all());
        }

        $name = $request->input('name', '');
        $quantity = $request->input('quantity', 0);
        $unit = $request->input('unit', 0);
        $type = $request->input('asset_type_id');
        $details = $request->input('details', []);

        /** @var MultichainService $multichainClient */
        $multichainClient = app('multichainService');

        $validAddress = $multichainClient->getAddressWithPermission('issue')['address'] ?? '';
        $customFields = array_merge($details, ['type' => AssetType::find($type)?->alias]);

        $txId = $multichainClient->issueAsset($validAddress, StringHelper::hyphenated($name), $quantity, $unit, $customFields);

        if ($txId === null) {
            return redirect()->back()->with('errors', ['There was an error with your submission. Please check the form and try again.']);
        }

        Asset::create([
            'name' => $name,
            'quantity' => $quantity,
            'unit' => $unit,
            'asset_type_id' => $type,
            'details' => $details,
            'creator_wallet_address' => $validAddress,
            'tx_id' => $txId,
            'alias' => StringHelper::hyphenated($name)
        ]);

        Session::flash('success', 'Asset created successfully');

        return redirect()->route('create-asset');
    }
}
