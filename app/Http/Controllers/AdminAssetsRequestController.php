<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Models\AssetsRequest;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminAssetsRequestController extends Controller
{
    public function index(Request $request): View
    {
        $assetsRequest = AssetsRequest::whereNotIn('status', [AssetsRequest::RESOLVED, AssetsRequest::REJECTED])
            ->where('owner_id', '=', $request->user()->id)->get();

        return view('admin/requests', compact('assetsRequest'));
    }

    public function approve(AssetsRequest $assetRequest, Request $request): RedirectResponse
    {
        $isValidPerson = MultichainService::isValidAddress($assetRequest->requestor->wallet_address);
        $hasPermission = MultichainService::hasPermissions(['receive'], $assetRequest->requestor->wallet_address);
        $user = $request->user();

        if (!$isValidPerson) {
            return redirect()->back()->with('errors', [MessageHelper::notAuthorizedUser()]);
        }

        if (!$hasPermission) {
            return redirect()->back()->with('errors', [MessageHelper::doesNotHavePermission()]);
        }

        $txPayload = $assetRequest->request_payload;
        $asset = MultichainService::assetInfo($assetRequest->asset);

        $txHex = MultichainService::multichain()->createrawexchange($txPayload['txid'], $txPayload['vout'], [$asset['name'] => $asset['issueqty']]);
        $decodedHex = MultichainService::multichain()->decoderawexchange($txHex);

        if (empty($decodedHex) || !$decodedHex['cancomplete']) {
            return redirect()->back()->with('errors', [MessageHelper::transactionFailure()]);
        }

        $lockedHex = MultichainService::multichain()->preparelockunspentfrom($user->wallet_address, [$asset['name'] => $asset['issueqty']]);
        $appendedTx = MultichainService::multichain()->appendrawexchange($txHex, $lockedHex['txid'], $lockedHex['vout'], [config('multichain.currency') => $assetRequest->commit_amount]);
        $txId = MultichainService::multichain()->sendrawtransaction($appendedTx['hex']);

        if ($txId === null) {
            return redirect()->back()->with('errors', [MessageHelper::transactionFailure()]);
        }

        Transaction::create([
            'tx_hex' => $txId
        ]);

        $assetRequest->status = AssetsRequest::RESOLVED;
        $assetRequest->save();

        $pendingRequests = AssetsRequest::where('owner_id', $user->id)->whereIn('status', [AssetsRequest::AWAITING_ADMINS_APPROVAL])->get();

        foreach ($pendingRequests as $req) {
            MultichainService::multichain()->lockunspent(true, [$req->request_payload]);

            $req->status = AssetsRequest::REJECTED;
            $req->save();
        }

        return redirect()->route('admin.requests')->with([
            'success' => 'Transaction has been successful',
            'data' => "Transaction Hash: $txId",
        ]);
    }

    public function reject(AssetsRequest $assetRequest): RedirectResponse
    {
        MultichainService::multichain()->lockunspent(true, [$assetRequest->request_payload]);

        $assetRequest->status = AssetsRequest::REJECTED;
        $assetRequest->save();

        return redirect()->route('admin.requests')->with('success', 'Request disapproved');
    }

    public function details(AssetsRequest $assetRequest, Request $request): View
    {
        $user = $request->user();

        $assetTransferred = collect(MultichainService::getAddressBalances($user->wallet_address))->where('name', $assetRequest->asset)->isNotEmpty();

        return view('admin.request-details', compact('assetRequest', 'assetTransferred'));
    }
}
