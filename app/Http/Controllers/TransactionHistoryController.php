<?php

namespace App\Http\Controllers;

use App\Models\AssetsRequest;
use Illuminate\Contracts\View\View;

class TransactionHistoryController extends Controller
{
    public function index(): View
    {
        $assetsRequest = AssetsRequest::whereIn('status', [AssetsRequest::RESOLVED, AssetsRequest::REJECTED])->get();

        return view('admin/transactions-history', compact('assetsRequest'));
    }
}
