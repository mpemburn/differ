<?php

namespace App\Services;

use App\Facades\Reader;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class SourceFilesService
{
    public const SOURCES_PATH = 'public/sources/';
    public function listSources(): array
    {
        $sources = Storage::allFiles(self::SOURCES_PATH);
        $list = [];
        collect($sources)->each(function ($source) use (&$list) {
            $name = str_replace(self::SOURCES_PATH, '', $source);
            $list[$source] = $name;
        })->sort();

        return $list;
    }

    public function hasDifferentContents(string $filename, string $contents): bool
    {
        $existingFile = Storage::path(self::SOURCES_PATH . $filename);
        $existingContents = Reader::contents($existingFile);

        if ($existingContents) {
            return $contents !== $existingContents;
        }

        return true;
    }

}
