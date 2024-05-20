<?php

namespace App\Http\Controllers;

use App\Services\CommandService;
use Illuminate\Http\Request;

class CommandController extends Controller
{
    public function index(CommandService $service)
    {
        return view('scanner', [
            'files' => $service->getUrlFileList()
        ]);
    }
}
