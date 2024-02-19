<?php
/*
 * Thanks to MogulChris (https://gist.github.com/MogulChris)
 * Based on the Gist: MogulChris/screenshot.php (https://gist.github.com/MogulChris/6f2facf768ac3f280e9ad765e531dd55)
 */
namespace App\Services;

use App\Facades\Curl;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome\ChromeProcess;
use Symfony\Component\Process\Exception\RuntimeException;
use Facebook\WebDriver\Exception\UnknownErrorException;

class BrowserScreenShotService
{
    protected $browser;
    protected string $saveDirectory;

    public function __construct(string $saveDirectory)
    {
        $this->saveDirectory = trim($saveDirectory);

        try {
            //Make a Chrome browser
            $process = (new ChromeProcess)->toProcess();
            $process->start();
            $options = (new ChromeOptions)->addArguments(['--disable-gpu', '--headless']);
            $capabilities = DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options);
            $driver = retry(5, function () use($capabilities) {
                return RemoteWebDriver::create('http://localhost:9515', $capabilities);
            }, 50);

            $this->browser = new Browser($driver);
        } catch (RuntimeException $rune) {
            echo 'RuntimeException: Failed to start a new screenshot process.' . PHP_EOL;
        } catch (UnknownErrorException $unke) {
            echo 'UnknownErrorException: Failed to start a new screenshot process.' . PHP_EOL;
        }
    }

    public function screenshot(string $url, string $title): bool
    {
        $filename = str_replace(' ', '', $title) . '.png';
        $filePath = Storage::path($this->saveDirectory . '/screenshots/') . '/' . $filename;

        // If we've created the file already, no need to redo
        if (file_exists($filePath)) {
            return true;
        }

        $this->browser->visit($url);

        try {
            //Start by full width and height
            $dims = $this->browser->script([
                'let body = document.body;
                let html = document.documentElement;
                let totalHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );
                return {width: body.offsetWidth, height: totalHeight};'
            ]);
            $bodyWidth = current($dims)['width'];
            $bodyHeight = current($dims)['height'];

            $size = new WebDriverDimension($bodyWidth, $bodyHeight);
            $this->browser->driver->manage()->window()->setSize($size);

            //Resize to full height for a complete screenshot
            $body = $this->browser->driver->findElement(WebDriverBy::tagName('body'));
            if (!empty($body)) {
                $currentSize = $body->getSize();

                //optional: scroll to bottom and back up, to trigger image lazy loading
                $this->browser->driver->executeScript('window.scrollTo(0, ' . $currentSize->getHeight() . ');');
                $this->browser->pause(1000);
                //scroll back to top of the page
                $this->browser->driver->executeScript('window.scrollTo(0, 0);');

                //set window to full height
                $size = new WebDriverDimension($bodyWidth, $bodyHeight);
                $this->browser->driver->manage()->window()->setSize($size);
            }

            $this->browser->pause(3000); //wait for 3s to give everything time to finish loading - probably better to actually check

            $image = $this->browser->driver->TakeScreenshot(); //$image is now the image data in PNG format

            //Save the image
            Storage::disk('local')->put($this->saveDirectory . '/screenshots/' . $filename, $image);

            return file_exists($filePath);
        } catch (\Exception $e) {
            echo 'Exception occurred creating screenshot for ' . $url . PHP_EOL;

            return false;
        }
    }
}
