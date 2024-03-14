<?php

namespace App\Http\Controllers;

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
            'dir' => 'screenshots/' . $sourceDir,
            'item' => $item,
        ]);
    }
}
