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
        $users = User::whereNull('wallet_address')->get()->all();

        return view('admin.new-users', compact('users'));
    }

    public function approve(User $user)
    {
        $multichainService = app('multichainService');

        $newAddress = $multichainService->getNewAddress();

        $user->wallet_address = $newAddress;
        $user->email_verified_at = Carbon::now();
        $user->save();

        Session::flash('success', 'User has been approved');

        return redirect()->route('new-users');
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
