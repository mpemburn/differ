<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ScannerService
{
    public function getUrlFileList(): array
    {
        $files = Storage::disk('public')->files('URLs');

        return preg_filter(['/URLs\//'], [''], $files);
    }
}
