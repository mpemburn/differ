<?php

namespace App\Http\Controllers;

use App\Services\CommandService;
use Illuminate\Http\Request;

class CommandController extends Controller
{
    public function index(CommandService $service)
    {
        return view('commands', [
            'files' => $service->getUrlFileList()
        ]);
    }

    public function getFileList(Request $request)
    {
        $filename = request('filename');
        $urls = (new CommandService())->getUrlArray($filename);

        return response()->json(['urls' => $urls]);
    }

    public function execute(Request $request)
    {
        $url = request('url');
        $testName = request('testName');
        $when = request('when');

        (new CommandService())->setTestName($testName)
            ->setWhen($when)
            ->run($url);

        return response()->json(['url' => $url]);
    }
}
