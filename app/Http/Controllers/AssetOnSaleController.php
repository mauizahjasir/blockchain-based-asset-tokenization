<?php

namespace App\Http\Controllers;


use App\Helpers\MessageHelper;
use App\Models\AssetsOnSale;
use Illuminate\Http\Request;

class AssetOnSaleController extends Controller
{
    public function putOnSale(Request $request)
    {
        $request->validate([
            'asset' => 'required'
        ]);

        AssetsOnSale::create([
            'asset' => $request->get('asset')
        ]);

        return redirect()->back()->with('success', 'Status changed successfully');
    }

    public function removeFromSale(Request $request)
    {
        $request->validate([
            'asset' => 'required'
        ]);

        $id = AssetsOnSale::where('asset', $request->get('asset'))->get()->first()?->id;

        if (empty($id)) {
            return redirect()->back()->with('errors', MessageHelper::submissionFailure());
        }

        AssetsOnSale::destroy($id);

        return redirect()->back()->with('success', 'Status changed successfully');
    }
}
