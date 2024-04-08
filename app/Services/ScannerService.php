<?php

namespace App\Services;

use App\Facades\Reader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class ScannerService
{
    protected bool $auth = false;
    protected bool $verbose = false;
    protected string $filename;
    protected string $testName;
    protected string $when;
    protected array $urlArray;

    public function getUrlFileList(): array
    {
        $files = Storage::disk('public')->files('URLs');

        return preg_filter(['/URLs\//'], [''], $files);
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        $testUrls = Storage::path('public/URLs/' . $filename);
        $this->urlArray = Reader::getContentsAsArray($testUrls);

        return $this;
    }

    public function setTestName(string $testName): self
    {
        $this->testName = $testName;

        return $this;
    }

    public function setWhen(string $when): self
    {
        $this->when = $when;

        return $this;
    }

    public function setVerbose(bool $verbose): self
    {
        $this->verbose = $verbose;

        return $this;
    }

    public function requiresAuth(bool $auth): self
    {
        $this->auth = $auth;

        return $this;
    }

    public function scan(): void
    {
        $path = 'public/screenshots/' . $this->testName. '/' . $this->when;
        $service = new BrowserScreenShotService($path);

        collect($this->urlArray)->each(function ($url) use ($service) {
            if (! str_starts_with($url, 'https')) {
                return;
            }

            Session::push('results', $url);

            if ($this->verbose) {
                echo 'Scanning: ' . $url . PHP_EOL;
            }

            $parts = parse_url($url);
            $title = str_replace('/', '', $parts['path']);

            $url = $this->setAuth($url);
            $service->login($url)->screenshot($url, $title);
        });
    }

    protected function setAuth(string $url): string
    {
        if ($this->auth && env('HTTP_AUTH_USERNAME') && env('HTTP_AUTH_PASSWORD')) {
            return str_replace('https://',
                'https://' . env('HTTP_AUTH_USERNAME') . ':' . env('HTTP_AUTH_PASSWORD') . '@',
                $url
            );
        }

        return $url;
    }

}
