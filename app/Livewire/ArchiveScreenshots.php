<?php

namespace App\Livewire;

use App\Facades\Image;
use App\Services\ArchiveService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class ArchiveScreenshots extends Component
{
    protected $listeners = [
        'refresh-event' => '$refresh'
    ];
    protected ArchiveService $archiveService;
    public string $sourcePath;

    public array $screenshotsSelected;
    public array $archivesSelected;
    public array $deletionsSelected;
    public string $confirmMove;
    public string $deletionSource;

    public function __construct()
    {
        $this->archiveService = new ArchiveService();
    }

    public function screenshotsChanged(): void
    {
        if (! empty($this->archivesSelected)) {
            $this->clearScreenshots();
        }
    }

    public function archivesChanged(): void
    {
        if (! empty($this->screenshotsSelected)) {
            $this->clearArchives();
        }
    }

    public function clearScreenshots(): void
    {
        $this->screenshotsSelected = [];
    }

    public function clearArchives(): void
    {
        $this->archivesSelected = [];
    }

    public function archive(): void
    {
        foreach ($this->screenshotsSelected as $screenshot) {
            $response = $this->archiveService->archive($screenshot);
            if ($response) {
                $this->confirm($response);
            }
        }

        $this->clearScreenshots();

        $this->dispatch('refresh-event');
    }

    public function unarchive(): void
    {
        foreach ($this->archivesSelected as $archive) {
            $response = $this->archiveService->unarchive($archive);
            if ($response) {
                $this->confirm($response);
            }
        }

        $this->clearArchives();

        $this->dispatch('refresh-event');
    }

    protected function confirm(string $path): void
    {
        $this->confirmMove = ArchiveService::directoryFromPath($path);

        $this->dispatch('openConfirmMoveModal');
    }

    public function keep(): void
    {
        $this->archiveService->keepBoth($this->sourcePath);
        $this->dispatch('closeConfirmMoveModal');
    }

    public function stop(): void
    {
        $this->dispatch('closeConfirmMoveModal');
    }

    public function replace(): void
    {
        $this->archiveService->replace($this->sourcePath);
        $this->dispatch('closeConfirmMoveModal');
    }

    public function confirmDelete(): void
    {
        if (empty($this->screenshotsSelected) && empty($this->archivesSelected)) {
            return;
        }

        $this->deletionSource = ! empty($this->archivesSelected)
            ? 'Archives'
            : 'Screenshots';
        $this->deletionsSelected = $this->deletionSource === 'Archives'
            ? $this->archivesSelected
            : $this->screenshotsSelected;

        $this->dispatch('openConfirmDeleteModal');
    }

    public function cancelDeletion(): void
    {
        $this->deletionsSelected = [];
        $this->dispatch('closeConfirmDeleteModal');
    }

    public function delete(): void
    {
        $this->archiveService->deleteSelected($this->deletionsSelected);

        $this->deletionsSelected = [];
        $this->clearScreenshots();
        $this->clearArchives();
        $this->dispatch('closeConfirmDeleteModal');

        $this->dispatch('refresh-event');
    }

    public function render(): Factory|Application|View|ApplicationContract
    {
        $screenshots = $this->archiveService->buildScreenshotsSelect();
        $archives = $this->archiveService->buildArchivedSelect();
        $numRows = max(count($screenshots), 15);

        return view('livewire.archive-screenshots', [
            'screenshots' => $screenshots,
            'archives' => $archives,
            'numRows' => $numRows,
        ]);
    }
}
