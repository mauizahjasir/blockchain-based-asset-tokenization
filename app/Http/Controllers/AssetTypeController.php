<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AssetTypeController extends Controller
{
    public function index()
    {
        return view('admin.create-asset-type');
    }

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string'
        ]);

        AssetType::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        Session::flash('success', 'Asset Type created successfully');

        return redirect()->route('create-asset-type');
    }
}
