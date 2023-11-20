<?php

namespace App\Http\Controllers;

use App\Models\AssetsRequest;
use Illuminate\Http\Request;

class OutgoingRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = AssetsRequest::where('requestor_id', $request->user()->id)->get();

        return view('client.outgoing-requests', compact('requests'));
    }
}
