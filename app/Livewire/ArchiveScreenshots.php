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
                $this->confirmMove = $response;
                $this->dispatch('openConfirmMoveModal');
            }
        }

        $this->screenshotsSelected = [];

        $this->dispatch('refresh-event');
    }

    public function unarchive(): void
    {
        foreach ($this->archivesSelected as $archive) {
            $this->archiveService->unarchive($archive);
        }

        $this->archivesSelected = [];

        $this->dispatch('refresh-event');
    }

    public function keep()
    {

    }

    public function stop()
    {

    }

    public function replace()
    {

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
