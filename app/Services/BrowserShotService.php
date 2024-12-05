<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class BrowserShotService
{
    const SCREENSHOT_PATH = 'public/screenshots/';

    public function __construct(string $saveDirectory)
    {
        $this->saveDirectory = self::SCREENSHOT_PATH . trim($saveDirectory);
        Storage::disk('local')->makeDirectory($this->saveDirectory);
    }

    public function screenshot(string $url, string $title): bool
    {
        if (empty($url)) {
            return false;
        }

        $title = empty($title) ? 'unknown' : $title;

        $filename = str_replace(' ', '', $title) . '.png';
        $filePath = Storage::path($this->saveDirectory) . '/' . $filename;

        // If we've created the file already, no need to redo
        if (file_exists($filePath)) {
            return true;
        }

        $browsershot = new Browsershot($url, true);
        $dimensions = $browsershot
            ->waitUntilNetworkIdle() // ensuring all additional resources are loaded
            ->evaluate("JSON.stringify({height: document.body.scrollHeight, width: document.body.scrollWidth})");

        $dimensions = json_decode($dimensions);

        $maxScreenshotHeight = floor(16 * 1024);

        for ($ypos = 0; $ypos < $dimensions->height; $ypos += $maxScreenshotHeight) {
            $height = min($dimensions->height - $ypos, $maxScreenshotHeight);
            $browsershot = new Browsershot($url, true);
            $browsershot
                ->waitUntilNetworkIdle()
                ->clip(0, $ypos, $dimensions->width, $height)
                ->timeout(120000) // handling timeout
                ->save($filePath);
        }

        return file_exists($filePath);
    }
}
