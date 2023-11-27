<?php

namespace App\Http\Controllers;

use App\Facades\MultichainService;
use App\Models\User;
use Illuminate\Contracts\View\View;
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

    public function __invoke(Request $request): View
    {
        return $request->user()->isAdmin()
            ? $this->adminView()
            : $this->clientView();
    }

    private function adminView(): View
    {
        $information = MultichainService::getInfo();

        return view('admin.home', ['data' => $information]);
    }

    private function clientView(): View
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->isVerified()
            ? view('client.home')
            : view('client.not-verified');
    }
}
