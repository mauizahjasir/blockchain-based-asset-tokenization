<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        return $user->isAdmin()
            ? $this->adminView()
            : $this->clientView();
    }

    private function adminView()
    {
        $users = User::whereNotNull('wallet_address')->get();

        return view('admin.home', compact('users'));
    }

    private function clientView()
    {
        return view('client.home');
    }
}
