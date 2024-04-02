<?php

namespace App\Http\Controllers;

use App\Facades\Image;
use App\Services\DiffService;

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

        $screenshots = Image::getScreenshots('screenshots/',  $sourceDir);
        return view('home', [
            'sources' => (new DiffService())->getSources(),
            'screenshots' => $screenshots,
            'item' => $item,
        ]);
    }
}
