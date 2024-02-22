<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DiffController extends Controller
{
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
}
