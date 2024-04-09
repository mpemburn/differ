<?php

namespace App\Services;

use App\Models\ComparisonResult;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class DiffService
{
    public const SOURCE_DIRECTORY = 'public/screenshots';

    public function getSources(): array
    {
        $directories = Storage::directories(self::SOURCE_DIRECTORY);

        $sources = [];
        collect($directories)->each(function ($directory) use (&$sources) {
            $timestamp = Storage::lastModified($directory);
            $dirName = str_replace(self::SOURCE_DIRECTORY . '/', '', $directory);
            $date = Carbon::createFromTimestamp($timestamp)->format('m/d/Y');

            // Only display if both before and after directories exist
            if (
                file_exists(Storage::path($directory . '/before'))
                && file_exists(Storage::path($directory . '/after'))
            ) {
                $sources[$dirName] = $date;
            }
        });

        return $sources;
    }

    public function getScreenshotDirectories(): array
    {
        $directories = Storage::directories(self::SOURCE_DIRECTORY);

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

        // Push all records into a session var.
        Session::push($source, [
            'source' => $source,
            'before_date' => $beforeDate ?: $now,
            'after_date' =>  $afterDate ?: $now,
            'filename' => $filename,
            'diff_percentage' => $percentage
        ]);
    }

    public function persistResults(): void
    {
        $source = request('source');
        $tests = ComparisonResult::where('source', $source)->max('test_number');
        $testNumber = $tests ? $tests + 1 : 1;

        $results = Session::get($source);

        if ($results) {
            collect($results)->each(function ($result) use ($testNumber) {
                $result['test_number'] = $testNumber;
                ComparisonResult::create($result);
            });
        }

        Session::forget($source);
    }

    protected function getFileDate(string $source, string $filename, string $when): ?string
    {
        $filepath = Storage::path("{self::SOURCE_DIRECTORY}/{$source}/{$when}/{$filename}");
        if (file_exists($filepath)) {
            $timestamp = File::lastModified($filepath);

            return Carbon::createFromTimestamp($timestamp)->toDateTimeString();
        }

        return null;
    }
}
