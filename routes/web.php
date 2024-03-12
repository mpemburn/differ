<?php

use App\Facades\Image;
use Illuminate\Support\Facades\Route;
use App\Services\BrowserScreenShotService;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/num', function () {
    $nums = Storage::path('public/dirs.txt');
    if (file_exists($nums)) {
        $contents = file_get_contents($nums);
        $array = explode("\n", $contents);

        natsort($array);

        collect($array)->each(function ($item) {
            echo $item . '<br>';
        });
    }

});

Route::get('/images', function () {
    $files = Image::getScreenshots('Clark');

    dd($files);
});

Route::get('/dev', function () {
    $path = 'public/Clark/after';

    $urls = [
        'https://www.clarku.edu/all-campus-events',
        'https://www.clarku.edu/event/clark-tank-marketing-pitch-competition-application-is-now-open'
    ];

    collect($urls)->each(function ($url) use ($path) {
        $service = new BrowserScreenShotService($path);
        $parts = explode('/', $url);
        $title = array_pop($parts);
        $service->screenshot($url, $title);
    });
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fetch_images', [\App\Http\Controllers\DiffController::class, 'fetchImages']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
