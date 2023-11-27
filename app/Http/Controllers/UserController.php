<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::whereNotNull('wallet_address')->get();

        return view('admin.all-users', compact('users'));
    }

    public function newUsers(): View
    {
        $users = User::whereNull('wallet_address')->orWhere('wallet_address', '=', '')->get()->all();

        return view('admin.new-users', compact('users'));
    }

    public function approve(User $user, Request $request): RedirectResponse
    {
        if ($request->user()->walletBalance(false) < (int)config('multichain.default_amount')) {
            $leastAmount = config('multichain.default_amount');
            return redirect()->back()->with('errors', "You do not have enough wallet balance of $leastAmount to approve this user.");
        }

        $newAddress = MultichainService::getNewAddress();

        if (empty($newAddress)) {
            return redirect()->back()->with('errors', MessageHelper::submissionFailure());
        }

        MultichainService::grantPermission($newAddress, 'receive,send');
        MultichainService::sendAssetFrom($request->user()->wallet_address, $newAddress, config('multichain.currency'), (int)config('multichain.default_amount'));

        $user->wallet_address = $newAddress;
        $user->email_verified_at = Carbon::now();
        $user->save();

        return redirect()->back()->with([
            'success' => 'User has been approved',
            'data' => "Wallet Address: $newAddress"
        ]);
    }
}
