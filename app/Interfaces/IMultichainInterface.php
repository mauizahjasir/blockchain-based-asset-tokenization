<?php

namespace App\Interfaces;

use App\Services\MultichainService;

interface IMultichainInterface
{
    public function setBlockChain($chain = ''): MultichainService;

    public function getNewAddress();
}
