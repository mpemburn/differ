<?php

namespace App\Livewire;

use App\Services\ScannerService;
use Livewire\Component;

class Scanner extends Component
{
    public $file;
    private ScannerService $service;

    public function __construct()
    {

        $this->service = (new ScannerService());
    }

    public function render()
    {
        return view('livewire.scanner', [
            'files' => $this->service->getUrlFileList()
        ]);
    }
}
