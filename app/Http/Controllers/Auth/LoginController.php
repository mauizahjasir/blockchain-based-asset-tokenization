<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\MultichainClient;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Add the registration form view
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $rpchost = '127.0.0.1'; // change if multichaind is not running locally
        $rpcport = 8340; // usually default-rpc-port in blockchain parameters
        $rpcuser = 'multichainrpc'; // see multichain.conf in blockchain directory
        $rpcpassword = 'HpADQYMNEawqxxHWJ3A8tuFShoMfV3j7mbR5CaqbHXox'; // see multichain.conf in blockchain directory
        $usessl = false; // use with SSL requires an proxy for MultiChain API endpoint

        $mc = new MultichainClient($rpchost, $rpcport, $rpcuser, $rpcpassword, $usessl);
        $mc->setoption(MC_OPT_CHAIN_NAME, 'asset-blockchain');
        $mc->setoption(MC_OPT_USE_CURL,true);


        /*return User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);*/
    }
}
