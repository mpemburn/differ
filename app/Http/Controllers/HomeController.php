<?php

namespace App\Http\Controllers;

use App\Services\DiffService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sourceDir = request('source');
        $item = request('item');

        return view('home', [
            'sources' => (new DiffService())->getSources(),
            'dir' => 'screenshots/' . $sourceDir,
            'item' => $item,
        ]);
    }
}
