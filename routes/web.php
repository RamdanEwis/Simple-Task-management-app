<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\GoogleCalendarController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// events routes
Route::resource('events',EventController::class)->middleware('auth');
//google/callback

/*Route::get('/authorize-google-calendar', [GoogleCalendarController::class,'authorize']);
Route::get('/authorize-google-calendar/callback', [GoogleCalendarController::class,'handleCallback']);*/

Route::get('authenticate', [GoogleCalendarController::class,'authenticate']);
Route::get('oauth/callback', [GoogleCalendarController::class,'callback']);
Route::get('calendar/list', [GoogleCalendarController::class,'listEvents'])->name('calendar.list');
