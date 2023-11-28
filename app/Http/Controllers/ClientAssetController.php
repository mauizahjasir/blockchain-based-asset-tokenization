<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ClientAssetController extends Controller
{
    public function index(Request $request): View
    {
        // For client
        $userAssets = MultichainService::getAddressBalances($request->user()->wallet_address);

        $assets = collect($userAssets)->reject(function ($item) {
            return $item['name'] === config('multichain.currency');
        })->map(function ($item) {
            $assetDetails = MultichainService::assetInfo($item['name']);
            $item['info'] = $assetDetails;
            return $item;
        });

        return view('client.assets', compact('assets'));
    }
}
