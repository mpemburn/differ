<?php

use App\Facades\Image;
use App\Facades\Reader;
use App\Http\Controllers\DiffController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommandController;
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
    $image = 'covid19-resources.png';
    $source = 'Sites_05-30-24';
    $results = PageLink::where('image', $image)
        ->where('test_name', $source)
        ->get();

    !d($results);

    // Do what thou wilt
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/differ', function () {
    return view('differ');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/get_results', [DiffController::class, 'getResults']);
Route::get('/get_links', [DiffController::class, 'getLinks']);
Route::post('/save_results', [DiffController::class, 'saveComparison']);

Route::get('/command', [CommandController::class, 'index'])->name('command');

Auth::routes();

