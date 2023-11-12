<?php

namespace App\Http\Controllers;

use App\Models\Asset;

class ClientAssetController extends Controller
{

    public function index()
    {
        $assets = Asset::with('creator', 'assetType')->get();

        return view('client.assets', compact('assets'));
    }
}
