<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
  * @method static array getScreenshots(string $directory);
 */
class Image extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'image';
    }
}
