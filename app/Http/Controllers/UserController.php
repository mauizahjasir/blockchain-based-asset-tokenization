<?php

namespace App\Http\Controllers;

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
}
