<?php

namespace App\Console\Commands;

use App\Facades\Reader;
use App\Services\BrowserScreenShotService;
use App\Services\CommandService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ScanUrlsCommand extends Command
{
    /**
     * artisan app:scanurls --test=sites_test.txt --name=Sites-test --when=before
     * artisan app:scanurls --test=sites_test.txt --name=Sites-test --when=after
     *
     * @var string
     */
    protected $signature = 'app:scanurls {--test=} {--name=} {--when=} {--auth=}';

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
        $name = $this->option('name');
        $when = $this->option('when');
        $auth = (bool) $this->option('auth');

        (new CommandService())->setFilename($test)
            ->setTestName($name)
            ->setWhen($when)
            ->setVerbose(true)
            ->requiresAuth($auth)
            ->scan();
    }
}
