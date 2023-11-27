<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Helpers\StringHelper;
use App\Models\AssetType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminAssetController extends Controller
{
    public function index(Request $request): View
    {
        // For admin
        $adminAssets = MultichainService::getAddressBalances($request->user()->wallet_address);

        $assets = collect($adminAssets)->reject(function ($item) {
            return $item['name'] === config('multichain.currency');
        })->map(function ($item) {
            $assetDetails = MultichainService::assetInfo($item['name']);
            $item['info'] = $assetDetails;
            return $item;
        });

        return view('admin.my-assets', compact('assets'));
    }

    public function createAssetView(): View
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
            'asset_type_id' => 'string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors()->all());
        }

        $name = $request->input('name', '');
        $quantity = $request->input('quantity', 0);
        $unit = $request->input('unit', 1);
        $type = $request->input('asset_type_id');
        $details = $request->input('details', []);
        $user = $request->user();

        $hasIssuePermission = MultichainService::hasPermissions(['issue'], $user->wallet_address);

        if (!$hasIssuePermission) {
            return redirect()->back()->with('errors', MessageHelper::doesNotHavePermission());
        }

        $customFields = array_merge($details, ['type' => AssetType::find($type)?->alias]);
        $txId = MultichainService::issueAsset($user->wallet_address, StringHelper::hyphenated($name), $quantity, $unit, $customFields);

        if ($txId === null) {
            return redirect()->back()->with('errors', MessageHelper::submissionFailure());
        }

        return redirect()->back()->with([
            'success' => MessageHelper::createSuccess('Asset'),
            'data' => "Response Hash: $txId"
        ]);
    }
}
