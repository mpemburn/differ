<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ArchiveService
{

    public function archive(string $screenshot): ?string
    {
        $archiveLocation = str_replace('/screenshots/', '/screenshots/Archived/', $screenshot);
        if (Storage::exists($archiveLocation)) {
            return $screenshot;
        }

        Storage::move($screenshot, $archiveLocation);

        return null;
    }

    public function unarchive(string $archive): ?string
    {
        $screenshotLocation = str_replace('/screenshots/Archived/', '/screenshots/', $archive);
        if (Storage::exists($screenshotLocation)) {
            return $archive;
        }

        Storage::move($archive, $screenshotLocation);

        return null;
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
        $directories = Storage::allDirectories('public/screenshots');

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
        return $this->buildSelect($this->listScreenshots(), 'public/screenshots/');
    }

    public function buildArchivedSelect(): array
    {
        return $this->buildSelect($this->listArchived(), 'public/screenshots/Archived/');
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
