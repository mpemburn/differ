<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ArchiveService
{
    public const SCREENSHOT_PATH = '/screenshots/';
    public const ARCHIVE_PATH = '/screenshots/Archived/';

    public static function directoryFromPath(string $path): string
    {
        return str_replace([
            'public' . self::ARCHIVE_PATH,
            'public' . self::SCREENSHOT_PATH
        ], '', $path);
    }

    public function archive(string $screenshot): ?string
    {
        $archiveLocation = str_replace(self::SCREENSHOT_PATH, self::ARCHIVE_PATH, $screenshot);
        if (Storage::exists($archiveLocation)) {
            return $screenshot;
        }

        Storage::move($screenshot, $archiveLocation);

        return null;
    }

    public function unarchive(string $archive): ?string
    {
        $screenshotLocation = str_replace(self::ARCHIVE_PATH, self::SCREENSHOT_PATH, $archive);
        if (Storage::exists($screenshotLocation)) {
            return $archive;
        }

        Storage::move($archive, $screenshotLocation);

        return null;
    }

    public function keepBoth(string $sourcePath): void
    {
        $copy = $sourcePath . '-copy';
        Storage::move($sourcePath, $copy);
        $destinationPath = $this->setDestinationPath($sourcePath);
        $destinationPath .=  '-copy';
        Storage::move($copy, $destinationPath);
    }

    public function replace(string $sourcePath): void
    {
        $destinationPath = $this->setDestinationPath($sourcePath);
        $tempPath = $destinationPath . '-temp';
        Storage::move($sourcePath, $tempPath);
        sleep(1);
        if (Storage::exists($destinationPath)) {
            Log::debug(storage_path($destinationPath));
            Storage::deleteDirectory($destinationPath);
        }
        Storage::move($tempPath, $destinationPath);
    }

    public function deleteSelected(array $selected): void
    {
        foreach ($selected as $directory) {
            Storage::deleteDirectory($directory);
        }
    }

    protected function setDestinationPath(string $sourcePath): string
    {
        return str_contains($sourcePath, self::ARCHIVE_PATH)
            ? str_replace(self::ARCHIVE_PATH, self::SCREENSHOT_PATH, $sourcePath)
            : str_replace(self::SCREENSHOT_PATH, self::ARCHIVE_PATH, $sourcePath);

    }

    public function listScreenshots(): Collection
    {
        return $this->listAll()->map(function ($directory) {
            if (str_contains($directory, 'Archived')) {
                return null;
            }

            return $directory;
        })->filter();
    }

    public function listArchived(): Collection
    {
        return $this->listAll()->map(function ($directory) {
            if (str_contains($directory, 'Archived')) {
                return $directory;
            }

            return null;
        })->filter();
    }

    public function listAll(): Collection
    {
        $directories = Storage::allDirectories('public' . self::SCREENSHOT_PATH);

        $list = collect($directories)->map(function ($directory) {
            if (
                str_ends_with($directory, 'before')
                || str_ends_with($directory, 'after')
            ) {
                return null;
            }

            return $directory;
        })->filter()->sort();

        return $list;
    }

    public function buildScreenshotsSelect(): array
    {
        return $this->buildSelect($this->listScreenshots(), 'public' . self::SCREENSHOT_PATH);
    }

    public function buildArchivedSelect(): array
    {
        return $this->buildSelect($this->listArchived(), 'public' . self::ARCHIVE_PATH);
    }

    protected function buildSelect(Collection $list, string $path): array
    {
        $select = [];
        $list->each(function ($directory) use ($path, &$select) {
            $name = str_replace($path, '', $directory);
            if ($name === $directory) {
                return;
            }
            $select[$directory] = $name;
        });

        return $select;
    }

}
