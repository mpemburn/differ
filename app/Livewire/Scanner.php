<?php

namespace App\Livewire;

use App\Services\CommandService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Scanner extends Component
{
    public string $file = '';
    public string $name = '';
    public string $when = '';
    public string $command = '';
    private CommandService $service;

    public function __construct()
    {
        $this->service = (new CommandService());
    }

    public function setFile(): void
    {
        $name = $this->file !== '' ? $this->makeName($this->file) : '';
        $this->name = $name;
        $this->command = '';
        $this->when = '';
    }

    public function generate(): void
    {
        if ($this->file === ''|| $this->name === ''|| $this->when === '') {
            $this->command = '';

            return;
        }

        $this->command = "php artisan app:scanurls --test={$this->file} --name={$this->name} --when={$this->when}";
    }

    public function copy()
    {
        $this->dispatch('copyToClipboard');
    }

    protected function makeName(string $filename): string
    {
        $date = Carbon::now()->format('-m-d-y');
        $name = basename($filename, '.txt');

        return ucfirst($name) . $date;
    }

    public function render()
    {
        return view('livewire.scanner', [
            'files' => $this->service->getUrlFileList()
        ]);
    }
}
