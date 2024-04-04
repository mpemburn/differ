<?php

namespace App\Http\Controllers;

use App\Services\ScannerService;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function index(ScannerService $service)
    {
        return view('scanner', [
            'files' => $service->getUrlFileList()
        ]);
    }
}
