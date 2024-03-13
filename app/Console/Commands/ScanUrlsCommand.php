<?php

namespace App\Console\Commands;

use App\Facades\Reader;
use App\Services\BrowserScreenShotService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ScanUrlsCommand extends Command
{
    /**
     * The name and signature of the console command.
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
//        $urlArray = ['https://news.test.clarku.edu/international-center-blog'];
        $urlArray = Reader::getContentsAsArray($testUrls);

        $path = 'public/' . $path. '/' . $when;

        $service = new BrowserScreenShotService($path);

        collect($urlArray)->each(function ($url) use ($service) {
            echo 'Scanning: ' . $url . PHP_EOL;

            $parts = explode('/', $url);
            $title = array_pop($parts);

            $service->login($url)->screenshot($url, $title);
        });
    }
}
