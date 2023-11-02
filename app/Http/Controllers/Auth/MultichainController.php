<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\MultichainClient;
use App\Services\MultichainService;

class MultichainController extends Controller
{
    public function getInfo()
    {
        /** @var MultichainService $multichainService */
        $multichainService = app('multichainService');
        $information = $multichainService->getInfo();

        return view('admin.get-info', ['data' => $information]);
    }
}
