<?php

namespace App\Http\Controllers;

use App\Models\ComparisonResult;
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
}
