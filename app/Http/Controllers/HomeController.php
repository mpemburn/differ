<?php

namespace App\Http\Controllers;

use App\Facades\Image;
use App\Models\ComparisonResult;
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
        $results = ComparisonResult::latestTest($sourceDir)->get();
        $lastTest = ComparisonResult::where('source', $sourceDir)->max('test_number');

        $testNumber = $lastTest ? $lastTest + 1 : 1;

        return view('home', [
            'sources' => (new DiffService())->getSources(),
            'screenshots' => $screenshots,
            'hasSource' => (bool) $sourceDir,
            'hasResults' => $results->count() > 0,
            'testNumber' => $testNumber,
            'item' => $item,
        ]);
    }
}
