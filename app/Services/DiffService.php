<?php

namespace App\Services;

use App\Models\ComparisonResult;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
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

    public function saveComparison(): void
    {
        $source = request('source');
        $filename = request('filename');
        $percentage = request('percentage');

        $beforeDate = $this->getFileDate($source, $filename, 'before');
        $afterDate = $this->getFileDate($source, $filename, 'after');
        $now = Carbon::now()->toDateTimeString();
        ComparisonResult::create([
            'source' => $source,
            'before_date' => $beforeDate ?: $now,
            'after_date' =>  $afterDate ?: $now,
            'filename' => $filename,
            'diff_percentage' => $percentage
        ]);
    }

    protected function getFileDate(string $source, string $filename, string $when): ?string
    {
        $filepath = Storage::path("public/screenshots/{$source}/{$when}/{$filename}");
        if (file_exists($filepath)) {
            $timestamp = File::lastModified($filepath);

            return Carbon::createFromTimestamp($timestamp)->toDateTimeString();
        }

        return null;
    }
}
