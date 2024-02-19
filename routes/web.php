<?php

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

Route::get('/dev', function () {
    $path = 'Clark';
//    $url = 'https://www.clarku.edu/all-campus-events';
    $url = 'https://clark:clarkadmin@www.testing.clarku.edu/all-campus-events';
//    $title = 'Home-prod';
    $title = 'Home-test';
    $service = new BrowserScreenShotService($path);
    $service->screenshot($url, $title);
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
