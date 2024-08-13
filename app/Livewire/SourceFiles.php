<?php

namespace App\Livewire;

use App\Facades\Reader;
use App\Services\SourceFilesService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class SourceFiles extends Component
{
    protected $listeners = [
        'refresh-event' => '$refresh'
    ];
    public string $sourceName;
    public string $initialContents;
    public string $editor;
    public bool $loading = true;
    public bool $canSave = false;
    public bool $showNewButton = true;
    public bool $showDeleteButton = false;
    protected SourceFilesService $service;

    public function __construct()
    {
        $this->service = new SourceFilesService();
    }
    public function editSource(): void
    {
        $filename = Storage::path(SourceFilesService::SOURCES_PATH . $this->sourceName);
        $this->editor = Reader::contents($filename);
        $this->initialContents = $this->editor;
        $this->showDeleteButton = true;
    }

    public function newSource(): void
    {
        $this->clear();
        $this->dispatch('focusTitle');
    }

    public function hasChanged(): void
    {
        $this->canSave = $this->initialContents !== $this->editor;
    }

    public function save(): void
    {
        $this->loading = true;
        Storage::put(SourceFilesService::SOURCES_PATH . $this->sourceName, $this->editor);
        sleep(1);
        $this->loading = false;
    }

    public function clear(): void
    {
        $this->sourceName = '';
        $this->editor = '';
        $this->showDeleteButton = false;
    }

    public function confirmDelete(): void
    {
        Log::debug($this->sourceName);
        $this->dispatch('openConfirmDeleteModal');
    }

    public function cancelDeletion(): void
    {
        $this->dispatch('closeConfirmDeleteModal');
    }

    public function delete(): void
    {
        $this->dispatch('closeConfirmDeleteModal');
    }

    public function render()
    {
        return view('livewire.source-files', [
            'sources' => $this->service->listSources(),
        ]);
    }
}
