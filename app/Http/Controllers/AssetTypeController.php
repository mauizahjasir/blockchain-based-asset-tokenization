<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\AssetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AssetTypeController extends Controller
{
    public function index()
    {
        return view('admin.create-asset-type');
    }

    public function store(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                Rule::unique('assets', 'name'),
            ]
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors()->all());
        }

        AssetType::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'alias' => StringHelper::hyphenated($request->input('name'))
        ]);

        Session::flash('success', 'Asset Type created successfully');

        return redirect()->route('create-asset-type');
    }
}
