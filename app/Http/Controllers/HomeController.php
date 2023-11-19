<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
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
        $information = MultichainService::getInfo();

        return view('admin.home', ['data' => $information]);
    }

    private function clientView()
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->isVerified()
            ? view('client.home')
            : view('client.not-verified');
    }
}
