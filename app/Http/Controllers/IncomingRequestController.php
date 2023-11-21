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
        $assetsRequest = AssetsRequest::where('owner_id', $request->user()->id)->get();

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
        $transfer = MultichainService::sendAssetFrom($user->wallet_address, User::adminWalletAddress(), $assetRequest->asset, (int)$assetInfo['issueqty']);

        if ($transfer === null) {
            return redirect()->back()->with('errors', MessageHelper::submissionFailure());
        }

        $assetRequest->status = AssetsRequest::AWAITING_BUYERS_APPROVAL;
        $assetRequest->save();

        return redirect()->back()->with('success', "Your asset has been transferred to Bank's wallet and awaiting approval");
    }
}
