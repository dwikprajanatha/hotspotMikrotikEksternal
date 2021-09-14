<?php

use Illuminate\Support\Facades\Route;

// ALL CONTROLLER DEFINE HERE
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;


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


// Route::post('/', [LoginController::class, 'home'])->name('hotspot.home');

Route::post('/loginHotspot', [LoginController::class, 'index'])->name('hotspot.login');

Route::get('/daftar', [LoginController::class, 'create'])->name('hotspot.register.view');
Route::post('/daftar', [LoginController::class, 'daftar'])->name('hotspot.register');

//SOCIAL MEDIA LOGIN
Route::get('auth/{provider}', [LoginController::class, 'redirect']);
Route::get('auth/{provider}/callback', [LoginController::class, 'callback']);

// Delete Callback Facebook
Route::post('user/facebook/delete',[LoginController::class, 'deleteCallbackFacebook'])->name('user.facebook.delete');


/* WEB UI START HERE !*/

// User Privacy
Route::get('user/privacy-policy',[LoginController::class, 'privacy'])->name('hotspot.privacy');

// Terms of Service
Route::get('user/terms-of-service',[LoginController::class, 'termsOfService'])->name('hotspot.tos');


// Tracking Facebook Deletion Request
Route::get('user/facebook/delete/track/{code}',[LoginController::class, 'deleteTracker'])->name('user.facebook.delete.track');


// Login
Route::get('/admin/login', [AdminController::class, 'showFormLogin'])->name('admin.login.view');
Route::post('/admin/login', [AdminController::class, 'loginAdmin'])->name('admin.login');

// Dashboard
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Hotspot User
Route::get('/admin/hotspot/{user}', [AdminController::class, 'HotspotUser'])->name('admin.user');

// Report
Route::get('/admin/report/{range}', [AdminController::class, 'reportUsage'])->name('admin.report.usage');