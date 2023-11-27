<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Helpers\MessageHelper;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(): View
    {
        $users = User::whereNotNull('email_verified_at')->where('user_type', Role::CLIENT)->get();

        return view('admin.manage-permissions', compact('users'));
    }

    public function grantPermission(User $user, Request $request): RedirectResponse
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

    public function revokePermission(User $user, Request $request): RedirectResponse
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

