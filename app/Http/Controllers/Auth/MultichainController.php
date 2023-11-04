<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    public function createAssetForm()
    {
        /** @var MultichainService $multichainClient */
        $multichainClient = app('multichainService');

        $validAddress = $multichainClient->getAddressWithPermission('issue');
    }
}

