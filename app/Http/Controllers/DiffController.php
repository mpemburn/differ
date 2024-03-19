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

    public function fetchImages()
    {
        $data = [];
        collect([
            'before' => 'Home-prod.png',
            'after' => 'Home-test.png'
        ])->each(function ($image, $source) use (&$data) {
            $imagePath = Storage::path('Clark/screenshots/' . $image);
            if (file_exists($imagePath)) {
                $contents = file_get_contents($imagePath);
                $base64Data = base64_encode($contents);
                $data[$source] = $base64Data;
            }
        });

        return response()->json(['images' => $data]);
    }

    public function saveComparison(Request $request)
    {
        $this->diffService->saveComparison();

        return response()->json(['success' => true]);
    }

    public function persistResults(Request $request)
    {
        $this->diffService->persistResults();

        return response()->json(['success' => true]);
    }

    public function getResults(Request $request)
    {
        $source = request('source');

        $results = ComparisonResult::latestTest($source)->get();

        return response()->json(['results' => $results]);
    }

}
