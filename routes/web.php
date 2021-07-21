<?php

use Illuminate\Support\Facades\Route;

// ALL CONTROLLER DEFINE HERE
use App\Http\Controllers\LoginController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('hotspot/login');
// });

Route::post('/loginHotspot', [LoginController::class, 'index'])->name('hotspot.login');

Route::get('/daftar', [LoginController::class, 'create'])->name('hotspot.register.view');
Route::post('/daftar', [LoginController::class, 'daftar'])->name('hotspot.register');

//SOCIAL MEDIA LOGIN
Route::get('auth/{provider}', [LoginController::class, 'redirect']);
Route::get('auth/{provider}/callback', [LoginController::class, 'callback']);