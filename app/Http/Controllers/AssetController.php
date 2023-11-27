<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Helpers\StringHelper;
use App\Models\AssetType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{
    public function clientAssets(Request $request)
    {
        // For admin
        $userAssets = MultichainService::getAddressBalances($request->user()->wallet_address);

        $assets = collect($userAssets)->reject(function ($item) {
           return $item['name'] === config('multichain.currency');
        })->map(function ($item) {
            $assetDetails = MultichainService::assetInfo($item['name']);
            $item['info'] = $assetDetails;
            return $item;
        });

        return view('client.my-assets', compact('assets'));
    }

    public function bankAssets()
    {
        $assets = collect(MultichainService::getAddressBalances(User::adminWalletAddress()))->reject(function ($item) {
            return $item['name'] === config('multichain.currency');
        })->map(function ($item) {
            $item['info'] = MultichainService::assetInfo($item['name']);
            return $item;
        })->values()->all();

        return view('client.bank-assets', compact('assets'));
    }
}
