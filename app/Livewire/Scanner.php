<?php

namespace App\Livewire;

use App\Services\ScannerService;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Scanner extends Component
{
    public bool $auth = false;
    public string $filename;
    public string $name = 'Livewire';
    public string $when = 'before';

    public string $file = 'news_test.txt';
    public string $results;
    private ScannerService $service;

    public function __construct()
    {

        $this->service = (new ScannerService());
    }

    public function scan(): void
    {
        Session::forget('results');
        $this->service->setFilename($this->file)
            ->setTestName($this->name)
            ->setWhen($this->when)
            ->requiresAuth($this->auth)
            ->scan();
    }

    public function refreshResults(): void
    {
        $this->results = '';
        if (Session::has('results')) {
            foreach (Session::get('results') as $result) {
                $this->results .=  $result . '<br>';
            }
        }
    }

    public function render()
    {
        return view('livewire.scanner', [
            'files' => $this->service->getUrlFileList()
        ]);
    }
}
