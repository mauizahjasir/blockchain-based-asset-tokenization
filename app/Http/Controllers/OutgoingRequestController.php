<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Models\AssetsRequest;
use App\Models\User;
use Illuminate\Http\Request;

class OutgoingRequestController extends Controller
{
    public function index(Request $request)
    {
        $assetsRequest = AssetsRequest::where('requestor_id', $request->user()->id)
            ->whereNotIn('status', [AssetsRequest::RESOLVED, AssetsRequest::REJECTED])->get();

        return view('client.outgoing-requests', compact('assetsRequest'));
    }

    public function approve(AssetsRequest $assetRequest, Request $request)
    {
        $user = $request->user();

        if (!MultichainService::hasPermissions(['send'], $user->wallet_address)) {
            return redirect()->back()->with('errors', MessageHelper::doesNotHavePermission());
        }

        $response = MultichainService::lockAssetAmount($user->wallet_address, config('multichain.currency'), $assetRequest->commit_amount);

        if ($response === null) {
            return redirect()->back()->with('errors', MessageHelper::submissionFailure());
        }

        $assetRequest->status = AssetsRequest::AWAITING_ADMINS_APPROVAL;
        $assetRequest->request_payload = $response;
        $assetRequest->save();

        return redirect()->back()->with('success', "Your amount has been locked until the admin approves / disapproves your request");
    }

    public function historicalData(Request $request)
    {
        $assetsRequest = AssetsRequest::where('requestor_id', $request->user()->id)
            ->whereIn('status', [AssetsRequest::RESOLVED, AssetsRequest::REJECTED])->get();

        return view('client.incoming-requests', compact('assetsRequest'));
    }
}
