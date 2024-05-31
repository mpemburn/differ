<?php

namespace App\Http\Controllers;

use App\Models\ComparisonResult;
use App\Models\PageLink;
use App\Services\DiffService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiffController extends Controller
{
    protected DiffService $diffService;

    public function __construct(DiffService $diffService)
    {
        $this->diffService = $diffService;
    }

    public function saveComparison(Request $request)
    {
        $this->diffService->saveComparison();

        return response()->json(['success' => true]);
    }

    public function getResults(Request $request)
    {
        $source = request('source');

        $results = ComparisonResult::latestTest($source)->orderBy('filename')->get();

        return response()->json(['results' => $results]);
    }

    public function getLinks(Request $request)
    {
        $testName = request('test_name');
        $image = request('image');

        $results = PageLink::where('image', $image)
            ->where('test_name', $testName)
            ->get();

        return response()->json(['results' => $results->toArray()]);
    }
}
