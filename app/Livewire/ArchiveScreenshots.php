<?php

namespace App\Livewire;

use App\Facades\Image;
use App\Services\ArchiveService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ArchiveScreenshots extends Component
{
    protected $listeners = [
        'refresh-event' => '$refresh'
    ];
    protected ArchiveService $archiveService;
    public string $sourcePath;

    public array $screenshotsSelected;
    public array $archivesSelected;
    public string $confirmMove;

    public function __construct()
    {
        $this->archiveService = new ArchiveService();
    }

    public function archive(): void
    {
        foreach ($this->screenshotsSelected as $screenshot) {
            $response = $this->archiveService->archive($screenshot);
            if ($response) {
                $this->confirm($response);
            }
        }

        $this->screenshotsSelected = [];

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

        $this->archivesSelected = [];

        $this->dispatch('refresh-event');
    }

    protected function confirm(string $path): void
    {
        $this->sourcePath = $path;
        $parts = explode('/', $path);
        $name = array_pop($parts);
        $this->confirmMove = $name;

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

    public function render()
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
