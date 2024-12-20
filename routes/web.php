<?php

use App\Facades\Image;
use App\Facades\Reader;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\DiffController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\SourceFilesController;
use App\Models\ComparisonResult;
use App\Models\PageLink;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Services\BrowserScreenShotService;
use Illuminate\Support\Facades\Session;
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

Route::get('/dev', function () {
    $url = 'https://www.uraniaswell.com/the-astronomy-of-astrology/';
    $path = 'Uraniaswell-12-05-24/before';
    $parts = parse_url($url);

    $title = str_replace('/', '', $parts['path']);
    (new \App\Services\BrowserShotService($path))->screenshot($url, $title);
    // Do what thou wilt
});

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/differ', function () {
    return view('differ');
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/get_results', [DiffController::class, 'getResults']);
    Route::get('/get_links', [DiffController::class, 'getLinks']);
    Route::post('/save_results', [DiffController::class, 'saveComparison']);

    Route::get('/command', [CommandController::class, 'index'])->name('command');
    Route::get('/get_file_list', [CommandController::class, 'getFileList'])->name('get_file_list');
    Route::post('/execute', [CommandController::class, 'execute']);

    Route::get('/archive', [ArchiveController::class, 'index'])->name('archive');
    Route::get('/sources', [SourceFilesController::class, 'index'])->name('sources');
});

Auth::routes();

