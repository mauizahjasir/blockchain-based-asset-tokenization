<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Services\MultichainClient;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    // Add the registration form view
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Ensure email is unique in the 'users' table
            'password' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        if ($user !== null) {
            $rpc_host = config('multichain.rpc_host');
            $rpc_port = config('multichain.rpc_port');
            $rpc_user = config('multichain.rpc_user');
            $rpc_password = config('multichain.rpc_password');
            $use_ssl = config('multichain.rpc_use_ssl');

            $mc = new MultichainClient($rpc_host, $rpc_port, $rpc_user, $rpc_password, $use_ssl);
            $mc->setoption(MC_OPT_CHAIN_NAME, 'asset-blockchain');
            $mc->setoption(MC_OPT_USE_CURL,true);

            $new_address = $mc->getnewaddress();

            $user->wallet_address = $new_address;
            $user->save();
        }

        return response()->json(['data' => $user]);
    }
}
