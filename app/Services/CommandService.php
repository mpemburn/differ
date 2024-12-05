<?php

namespace App\Services;

use App\Facades\Reader;
use App\Models\PageLink;
use Illuminate\Support\Facades\Storage;

class CommandService
{
    protected bool $auth = false;
    protected bool $verbose = false;
    protected string $filename;
    protected string $testName;
    protected string $when;
    protected array $urlArray;

    public function getUrlFileList(): array
    {
        $files = Storage::disk('public')->files('sources');

        return preg_filter(['/sources\//'], [''], $files);
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;
        $this->urlArray = $this->getUrlArray($filename);

        return $this;
    }
    public function getUrlArray(string $filename): array
    {
        $testUrls = Storage::path('public/sources/' . $filename);
        return Reader::getContentsAsArray($testUrls);
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
        $service = new BrowserShotService($path);

        collect($this->urlArray)->each(function ($url) use ($service) {
            if (! str_starts_with($url, 'https')) {
                return;
            }

            if ($this->verbose) {
                echo 'Scanning: ' . $url . PHP_EOL;
            }

            $parts = parse_url($url);
            $title = str_replace('/', '', $parts['path']);

            $url = $this->setAuth($url);
            $service->login($url)->screenshot($url, $title);
            PageLink::firstOrCreate([
                'image' => $title . '.png',
                'url' => $url,
                'when' => $this->when,
                'source_file' => $this->filename,
                'test_name' => $this->testName,
            ]);
        });
    }

    public function run(string $url): bool
    {
        $path = $this->testName. '/' . $this->when;

        $service = new BrowserShotService($path);
        $parts = parse_url($url);
        $title = str_replace('/', '', $parts['path']);

        $url = $this->setAuth($url);
        $service->screenshot($url, $title);
        PageLink::firstOrCreate([
            'image' => $title . '.png',
            'url' => $url,
            'when' => $this->when,
            'source_file' => $this->filename,
            'test_name' => $this->testName,
        ]);

        return true;
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
