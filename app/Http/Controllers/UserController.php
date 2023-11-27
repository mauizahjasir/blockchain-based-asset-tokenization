<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereNull('wallet_address')->orWhere('wallet_address', '=', '')->get()->all();

        return view('admin.new-users', compact('users'));
    }

    public function allUsers()
    {
        $users = User::whereNotNull('wallet_address')->get();

        return view('admin.all-users', compact('users'));
    }

    public function approve(User $user, Request $request)
    {
        if ($request->user()->walletBalance(false) < (int)config('multichain.default_amount')) {
            return redirect()->back()->with('errors', 'You do not have enough wallet balance to approve this user.');
        }

        $newAddress = MultichainService::getNewAddress();

        if (empty($newAddress)) {
            return redirect()->back()->with('errors', MessageHelper::submissionFailure());
        }

        MultichainService::grantPermission($newAddress, 'receive');
        MultichainService::grantPermission($newAddress, 'send');
        MultichainService::sendAssetFrom($request->user()->wallet_address, $newAddress, config('multichain.currency'), (int)config('multichain.default_amount'));

        $user->wallet_address = $newAddress;
        $user->email_verified_at = Carbon::now();
        $user->save();

        return redirect()->back()->with([
            'success' => 'User has been approved',
            'data' => "Wallet Address: $newAddress"
        ]);
    }

    public function grantPermission(User $user, Request $request)
    {
        $request->validate([
            'permission' => 'required'
        ]);

        $response = MultichainService::grantPermission($user->wallet_address, $request->get('permission'));

        if (empty($response)) {
            return redirect()->back()->with('errors', MessageHelper::permissionFailure());
        }

        return redirect()->back()->with('success', MessageHelper::permissionSuccess());
    }

    public function revokePermission(User $user, Request $request)
    {
        $request->validate([
            'permission' => 'required'
        ]);

        $response = MultichainService::revokePermission($user->wallet_address, $request->get('permission'));

        if (empty($response)) {
            return redirect()->back()->with('errors', MessageHelper::permissionFailure('revoke'));
        }

        return redirect()->back()->with('success', MessageHelper::permissionSuccess('revoked'));
    }
}
