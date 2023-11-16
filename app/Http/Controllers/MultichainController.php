<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Models\Role;
use App\Models\User;

class MultichainController extends Controller
{
    public function getInfo()
    {
        $information = MultichainService::getInfo();

        return view('admin.get-info', ['data' => $information]);
    }

    public function managePermissions()
    {
        $users = User::whereNotNull('email_verified_at')->where('user_type', Role::CLIENT)->get();

        return view('admin.manage-permissions', compact('users'));
    }
}

