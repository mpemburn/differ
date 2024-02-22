<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class Image
{
    public function getScreenshots(string $directory): array
    {
        $files = Storage::disk('public')->allFiles($directory);
        $result = [];
        collect($files)->each(function ($file) use (&$result) {
            if (str_contains($file, '.DS_Store')) {
                return null;
            }
            $when = str_contains($file, 'before') ? 'before' : 'after';

            $result[$file] = $when;
        })->filter();

        return $result;
    }

    public function getName(string $filename): string
    {
        $nameParts = explode('/', $filename);

        return end($nameParts);
    }
}
