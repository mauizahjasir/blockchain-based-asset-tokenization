<?php

namespace App\Http\Controllers;


use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Models\AssetsRequest;
use App\Models\User;
use Illuminate\Http\Request;

class IncomingRequestController extends Controller
{
    public function index(Request $request)
    {
        $assetsRequest = AssetsRequest::where('owner_id', $request->user()->id)
            ->whereNotIn('status', [AssetsRequest::RESOLVED, AssetsRequest::REJECTED])->get();

        return view('client.incoming-requests', compact('assetsRequest'));
    }

    public function approve(AssetsRequest $assetRequest, Request $request)
    {
        $user = $request->user();

        if (!MultichainService::hasPermissions(['send'], $user->wallet_address)) {
            return redirect()->back()->with('errors', MessageHelper::doesNotHavePermission());
        }

        $assetInfo = MultichainService::assetInfo($assetRequest->asset);

        // Transferring asset to admin's wallet for approval
        $txid = MultichainService::signedTransaction($user->wallet_address, User::adminWalletAddress(), $assetRequest->asset, $assetInfo['issueqty']);

        if ($txid === null) {
            return redirect()->back()->with('errors', 'Failed transferring asset to Admin.');
        }

        $assetRequest->status = AssetsRequest::AWAITING_BUYERS_APPROVAL;
        $assetRequest->save();

        $remainingRequests = AssetsRequest::where('asset', $assetRequest->asset)->whereIn('status', [AssetsRequest::AWAITING_OWNER_APPROVAL])->get();

        foreach ($remainingRequests as $remReq) {
            $remReq->status = AssetsRequest::REJECTED;
            $remReq->save();
        }

        return redirect()->back()->with('success', "Your asset has been transferred to Bank's wallet and awaiting approval");
    }

    public function historicalData(Request $request)
    {
        $assetsRequest = AssetsRequest::where('owner_id', $request->user()->id)
            ->whereIn('status', [AssetsRequest::RESOLVED, AssetsRequest::REJECTED])->get();

        return view('client.incoming-requests-history', compact('assetsRequest'));
    }

    public function reject(AssetsRequest $assetRequest)
    {
        $assetRequest->status = AssetsRequest::REJECTED_BY_OWNER;
        $assetRequest->save();

        return redirect()->back()->with('success', 'Request has been rejected');
    }
}
