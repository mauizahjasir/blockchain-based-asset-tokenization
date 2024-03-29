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
        $this->multichainService->setOption(MC_OPT_USE_CURL,true);
    }

    public function setBlockChain($chain = ''): MultichainService
    {
        $this->multichainService->setOption(MC_OPT_CHAIN_NAME, $chain);

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

    public function multichain()
    {
        return $this->multichainService;
    }

    public function getAddressWithPermission(string $permission = '')
    {
        return collect($this->multichainService->listpermissions($permission))->first();
    }

    public function issueAsset($address, $name, $quantity = 0, $unit = 1, $customFields = [])
    {
        return $this->multichainService->issue($address, ['name' => $name, 'open' => true], (int)$quantity, (int)$unit, 0, $customFields);
    }

    public function sendAssetFrom($fromAddress, $toAddress, $name, $quantity = 0)
    {
        return $this->multichainService->sendfrom($fromAddress, $toAddress, [$name => $quantity]);
    }

    public function isValidAddress(string $address)
    {
        $response = $this->multichainService->validateaddress($address);

        return is_array($response)
            ? $response['isvalid']
            : false;
    }

    public function hasPermissions(array $permissions, string $address)
    {
        $response = $this->multichainService->listpermissions(implode(',', $permissions), $address);
        $allPermissions = collect($response)->pluck('type')->all();

        return empty(array_diff($permissions, $allPermissions));
    }

    public function permissions($address, $permissions = '*')
    {
        return $this->multichainService->listpermissions($permissions, $address);
    }

    public function grantPermission($address, $permission)
    {
        return $this->multichainService->grant($address, $permission);
    }

    public function revokePermission($address, $permission)
    {
        return $this->multichainService->revoke($address, $permission);
    }

    public function getAddressBalances($address)
    {
        return $this->multichainService->getaddressbalances($address, 0);
    }

    public function getTotalBalances()
    {
        return $this->multichainService->gettotalbalances();
    }

    public function assetInfo(string $asset = '')
    {
        return $this->multichainService->getassetinfo($asset);
    }

    public function lockAssetAmount($address, $asset, $amount)
    {
        return $this->multichainService->preparelockunspentfrom($address, [$asset => (int)$amount]);
    }

    public function signedTransaction($fromAddress, $toAddress, $asset, $quantity)
    {
        $txhex = $this->multichainService->createrawsendfrom($fromAddress, [$toAddress => [$asset => (int)$quantity]]);
        $result = $this->multichainService->signrawtransaction($txhex);

        $txid = $this->multichainService->sendrawtransaction($result['hex']);

        return $txid;
    }
}
