<?php

namespace App\Console\Commands;

use App\Facades\Reader;
use App\Services\BrowserScreenShotService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ScanUrlsCommand extends Command
{
    /**
     * artisan app:scanurls --test=sites_test.txt --path=Sites --when=before
     * artisan app:scanurls --test=sites_test.txt --path=Sites --when=after
     *
     * @var string
     */
    protected $signature = 'app:scanurls {--test=} {--path=} {--when=} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $test = $this->option('test');
        $path = $this->option('path');
        $when = $this->option('when');

        $testUrls = Storage::path('public/URLs/' . $test);
        $urlArray = Reader::getContentsAsArray($testUrls);

        if ($this->process($path, $when, $urlArray)) {
            $retry = Storage::path('retry.txt');
            $retryArray = Reader::getContentsAsArray($retry);
            if (! empty($retryArray)) {
                $this->process($path, $when, $retryArray);
            }
        }
    }

    protected function process(string $path, string $when, array $urls): bool
    {
        Storage::delete('retry.txt');

        $path = 'public/screenshots/' . $path. '/' . $when;
        $service = new BrowserScreenShotService($path);

        collect($urls)->each(function ($url) use ($service) {
            echo 'Scanning: ' . $url . PHP_EOL;

            $parts = parse_url($url);
            $title = str_replace('/', '', $parts['path']);

            $service->login($url)->screenshot($url, $title);
        });

        return ! file_exists(Storage::path('retry.txt'));
    }
}
