<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MultichainService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'multichainService'; // Replace 'exampleObject' with your object name
    }
}
