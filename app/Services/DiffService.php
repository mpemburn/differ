<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DiffService
{

    public function getScreenshotDirectories(): array
    {
        $directories = Storage::directories('public/screenshots');

        return collect($directories)->map(function ($directory) {
            $timestamp = Storage::lastModified($directory);
            $date = Carbon::createFromTimestamp($timestamp)->format('m/d/Y');

            return [$directory => $date];
        })->toArray();
    }
}
