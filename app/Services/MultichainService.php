<?php

namespace App\Services;

use App\Interfaces\IMultichainInterface;

class MultichainService implements IMultichainInterface
{
    private $rpcHost;
    private $rpcPort;
    private $rpcUser;
    private $rpcPassword;
    private $rpcUseSsl;

    private $multichainService;

    public function __construct()
    {
        $this->rpcHost = config('multichain.rpc_host');
        $this->rpcPort = config('multichain.rpc_port');
        $this->rpcUser = config('multichain.rpc_user');
        $this->rpcPassword = config('multichain.rpc_password');
        $this->rpcUseSsl = config('multichain.rpc_use_ssl');

        $this->multichainService = new MultichainClient($this->rpcHost, $this->rpcPort, $this->rpcUser, $this->rpcPassword, $this->rpcUseSsl);
        $this->multichainService->setoption(MC_OPT_USE_CURL,true);
    }

    public function setBlockChain($chain = ''): MultichainService
    {
        $this->multichainService->setoption(MC_OPT_CHAIN_NAME, 'asset-blockchain');

        return $this;
    }

    public function getNewAddress()
    {
        return $this->multichainService->getnewaddress();
    }

    public function getInfo()
    {
        return $this->multichainService->getinfo();
    }
}
