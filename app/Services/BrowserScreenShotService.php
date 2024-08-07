<?php
/*
 * Thanks to MogulChris (https://gist.github.com/MogulChris)
 * Based on the Gist: MogulChris/screenshot.php (https://gist.github.com/MogulChris/6f2facf768ac3f280e9ad765e531dd55)
 */
namespace App\Services;

use Exception;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverDimension;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome\ChromeProcess;
use Symfony\Component\Process\Exception\RuntimeException;
use Facebook\WebDriver\Exception\UnknownErrorException;

class BrowserScreenShotService
{
    const DEFAULT_BROWSER_WIDTH = 1920;
    const OPTIONS = [
        '--headless',
        '--no-sandbox',
        '--ignore-certificate-errors',
        '--ignore-ssl-errors',
        '--disable-dev-shm-usage',
        '--disable-gpu',
        '--log-level=3',
        'enable-features=NetworkServiceInProcess',
        'disable-features=NetworkService'
    ];

    protected $driver;
    protected $browser;
    protected string $saveDirectory;

    public function __construct(string $saveDirectory)
    {
        $this->saveDirectory = trim($saveDirectory);

        try {

            //Make a Chrome browser
            $process = (new ChromeProcess)->toProcess();
            $process->start();
            $options = (new ChromeOptions)->addArguments(self::OPTIONS);
            $capabilities = DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options);
            $this->driver = retry(5, function () use($capabilities) {
                return RemoteWebDriver::create('http://localhost:9515', $capabilities);
            }, 50);

            $this->browser = new Browser($this->driver);
        } catch (RuntimeException $rune) {
            echo 'RuntimeException: Failed to start a new screenshot process.' . PHP_EOL;
        } catch (UnknownErrorException $unke) {
            echo 'UnknownErrorException: Failed to start a new screenshot process.' . PHP_EOL;
        }
    }

    public function login (string $url): self
    {
        try {
            $this->browser->visit($url)
                ->waitForText(env('ADFS_SIGN_ON_TEXT'));
        } catch (Exception $e) {
            return $this;
        }

        $this->browser->type('UserName', env('ADFS_USER_EMAIL'))
            ->type('Password', env('ADFS_USER_PASSWORD'))
            ->press('#submitButton')
            ->pause(3000);

        return $this;
    }

    public function screenshot(string $url, string $title): bool
    {
        if (empty($url)) {
            echo 'No URL provided.' . PHP_EOL;
            return false;
        }

        $title = empty($title) ? 'unknown' : $title;

        $filename = str_replace(' ', '', $title) . '.png';
        $filePath = Storage::path($this->saveDirectory) . '/' . $filename;

        // If we've created the file already, no need to redo
        if (file_exists($filePath)) {
            return true;
        }

        $this->browser->visit($url);

        try {
            // Calculate screen height
            $screenHeight = $this->getScreenHeight();

            $this->browser->pause(1000);
            // Set dimensions for screenshot
            $size = new WebDriverDimension(self::DEFAULT_BROWSER_WIDTH, $screenHeight);
            $this->browser->driver->manage()->window()->setSize($size);

            // Workaround to prevent timeout
            $this->driver->get($url)->getPageSource();

            $image = $this->browser->driver->TakeScreenshot();

            // Save the image
            Storage::disk('local')->put($this->saveDirectory . '/' . $filename, $image);

            $this->browser->quit();

            return file_exists($filePath);
        } catch (\Exception $e) {
            echo 'Exception occurred creating screenshot for ' . $url . PHP_EOL;
            echo $e->getMessage() . PHP_EOL;
            Storage::append('retry.txt', $url);

            return false;
        }
    }

    protected function getScreenHeight(): int
    {
        $dims = $this->browser->script([
            'let body = document.body;
                let html = document.documentElement;
                let totalHeight = Math.max(
                    body.scrollHeight,
                    body.offsetHeight,
                    html.clientHeight,
                    html.scrollHeight,
                    html.offsetHeight
                );

                return {height: totalHeight};'
        ]);

        return current($dims)['height'];
    }
}
