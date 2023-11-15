<?php

return [
    'rpc_host'     => env('RPC_HOST', '127.0.0.1'),
    'rpc_port'     => env('RPC_PORT', '8340'),
    'rpc_user'     => env('RPC_USER', 'multichainrpc'),
    'rpc_password' => env('RPC_PASSWORD', ''),
    'rpc_use_ssl'  => (bool) env('RPC_USE_SSL', false),
    'blockchain_name'  => env('CHAINNAME', ''),
    'currency'   => env('CURRENCY', ''),
    'default_amount'   => env('DEFAULT_AMOUNT', 500)
];
