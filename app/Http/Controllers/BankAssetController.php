<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Models\User;
use Illuminate\Contracts\View\View;

class BankAssetController extends Controller
{
    public function index(): View
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
