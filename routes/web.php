<?php

use App\Facades\Image;
use App\Facades\Reader;
use App\Http\Controllers\DiffController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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
    $sources = (new \App\Services\DiffService())->getSources();
    foreach($sources as $source => $date) {
        !d($source);
    }

});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/fetch_images', [DiffController::class, 'fetchImages']);
Route::post('/save_results', [DiffController::class, 'saveResults']);

Auth::routes();

