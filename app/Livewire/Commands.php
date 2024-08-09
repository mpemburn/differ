<?php

namespace App\Livewire;

use App\Services\CommandService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Commands extends Component
{
    public string $file = '';
    public string $name = '';
    public string $when = '';
    public string $command = '';
    public string $auth = '';

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
        $this->auth = false;
    }

    public function generate(): void
    {
        if ($this->file === ''|| $this->name === ''|| $this->when === '') {
            $this->command = '';

            return;
        }

        $requiresAuth = $this->auth === '1' ? ' --auth="true"' : '';
        $this->command = "php artisan app:scanurls --test={$this->file} --name={$this->name} --when={$this->when} {$requiresAuth}";
    }

    public function copy()
    {
        $this->dispatch('copyToClipboard');
    }

    public function run()
    {
        $this->dispatch('runCommand');
    }

    protected function makeName(string $filename): string
    {
        $date = Carbon::now()->format('-m-d-y');
        $name = basename($filename, '.txt');

        return ucfirst($name) . $date;
    }

    public function render()
    {
        return view('livewire.commands', [
            'files' => $this->service->getUrlFileList()
        ]);
    }
}
