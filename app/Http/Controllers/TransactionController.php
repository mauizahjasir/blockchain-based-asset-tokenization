<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Models\AssetsOnSale;
use App\Models\AssetsRequest;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(): View
    {
        $assetsRequest = AssetsRequest::whereNotIn('status', [AssetsRequest::RESOLVED, AssetsRequest::REJECTED])->get();

        return view('admin/transactions', compact('assetsRequest'));
    }

    public function approve(AssetsRequest $assetRequest, Request $request): RedirectResponse
    {
        if (in_array($assetRequest->status, [AssetsRequest::REJECTED_BY_BUYER, AssetsRequest::REJECTED_BY_OWNER])) {
            return redirect()->back()->with('errors', 'The request has been rejected by collaborating parties');
        }

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

        MultichainService::signedTransaction($user->wallet_address, $assetRequest->owner->wallet_address, config('multichain.currency'), $assetRequest->commit_amount);

        Transaction::create([
            'tx_hex' => $txId
        ]);

        AssetsOnSale::where('asset', $assetRequest->asset)
            ->delete();

        $assetRequest->status = AssetsRequest::RESOLVED;
        $assetRequest->save();

        return redirect()->route('assets.transactions')->with(['success' => 'Transaction has been successful', 'data' => "Transaction Hash: $txId"]);
    }

    public function reject(AssetsRequest $assetRequest, Request $request): RedirectResponse
    {
        $assetInfo = MultichainService::assetInfo($assetRequest->asset);

        $txid = MultichainService::signedTransaction($request->user()->wallet_address, $assetRequest->owner->wallet_address, $assetRequest->asset, $assetInfo['issueqty']);

        if ($txid === null) {
            return redirect()->back()->with('errors', 'Failed reverting the transaction.');
        }

        MultichainService::multichain()->lockunspent(true, [$assetRequest->request_payload]);

        $assetRequest->status = AssetsRequest::REJECTED;
        $assetRequest->save();

        return redirect()->back()->with('success', 'Request disapproved');
    }

    public function requestDetails(AssetsRequest $assetRequest, Request $request)
    {
        $user = $request->user();

        $assetTransferred = collect(MultichainService::getAddressBalances($user->wallet_address))->where('name', $assetRequest->asset)->isNotEmpty();

        return view('admin.request-detail-form', compact('assetRequest', 'assetTransferred'));
    }
}
