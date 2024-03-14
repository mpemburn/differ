<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class Image
{
    public function getScreenshots(string $directory): array
    {
        $index = 0;
        $files = Storage::disk('public')->allFiles($directory);
        $result = [];
        collect($files)->each(function ($file) use (&$index, &$result) {
            if (str_contains($file, '.DS_Store')) {
                return null;
            }
            $when = str_contains($file, '/before/') ? 'before' : 'after';
            if ($when === 'before') {
                $index++;
            }

            $result[$file] = ['when' => $when, 'index' => $index];
        })->filter();

        return $result;
    }

    public function getName(string $filename): string
    {
        $nameParts = explode('/', $filename);

        return end($nameParts);
    }
}
