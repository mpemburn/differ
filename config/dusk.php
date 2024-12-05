<?php

use Facebook\WebDriver\Chrome\ChromeOptions;

$options = (new ChromeOptions())->addArguments([
    '--disable-gpu',
    '--headless',
    '--no-sandbox',
]);

$options->setBinary('/Users/mpemburn/Dev/chromedriver-mac-x64/chromedriver');

return [
    'driver' => 'chrome',
    'chromeOptions' => $options,
];
