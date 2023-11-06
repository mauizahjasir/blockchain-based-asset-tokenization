<?php

namespace App\Http\Controllers;

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

