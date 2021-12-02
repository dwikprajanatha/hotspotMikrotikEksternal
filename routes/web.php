<?php

use Illuminate\Support\Facades\Route;

// ALL CONTROLLER DEFINE HERE
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\MikrotikController;


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

Route::get('user/statusHotspot', [LoginController::class, 'redirectStatus'])->name('hotspot.redirect.status');
Route::post('user/statusHotspot', [LoginController::class, 'status'])->name('hotspot.status');

Route::get('/forgotPassword', [LoginController::class, 'showforgotPassword'])->name('hotspot.forgot.view');
Route::post('/forgotPassword', [LoginController::class, 'forgotPassword'])->name('hotspot.forgot');

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



/**
 * 
 * ADMIN START HERE
 * 
 */

Route::group(['middleware' => 'auth:web'], function() {
    
    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // List Admin Account
    Route::get('/admin/account', [AdminController::class, 'listAccount'])->name('admin.account');

    // Create Admin Account
    Route::get('/admin/account/create', [AdminController::class, 'showCreateAccount'])->name('admin.account.create.view');
    Route::post('/admin/account/create', [AdminController::class, 'createAccount'])->name('admin.account.create');

    // List Admin Account
    Route::get('/admin/account', [AdminController::class, 'listAccount'])->name('admin.account');

    // Edit Admin Account
    Route::get('/admin/account/edit/{id}', [AdminController::class, 'editAccount'])->name('admin.account.edit');
    Route::post('/admin/account/update', [AdminController::class, 'updateAccount'])->name('admin.account.update');

    // Delete Admin Account
    Route::get('/admin/account/delete/{id}', [AdminController::class, 'deleteAccount'])->name('admin.account.delete');

    // Enable Admin Account
    Route::get('/admin/account/enable/{id}', [AdminController::class, 'enableAccount'])->name('admin.account.enable');

    // Hotspot User
    Route::get('/admin/hotspot/{user}', [AdminController::class, 'hotspotUser'])->name('admin.user');

    // Edit Hotspot User
    Route::get('/admin/hotspot/{user}/edit/{id}', [AdminController::class, 'editUser'])->name('admin.user.edit');
    
    // Disable Hotspot User
    Route::get('/admin/hotspot/{user}/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');

    // Enable Hotspot User
    Route::get('/admin/hotspot/{user}/enable/{id}', [AdminController::class, 'enableUser'])->name('admin.user.enable');
    
    // Report
    Route::get('/admin/report/{range}', [AdminController::class, 'reportUsage'])->name('admin.report.usage');
    

    //Mikrotik (admin Network & Root admin)

    // Kategori User
    Route::get('/admin/mikrotik/kategori_user', [MikrotikController::class, 'listGroupUser'])->name('admin.mikrotik.listGroupUser');
    // Tambah Kategori User
    Route::get('/admin/mikrotik/kategori_user/add', [MikrotikController::class, 'showCreateGroupUser'])->name('admin.mikrotik.showCreateGroupUser');
    Route::post('/admin/mikrotik/kategori_user/add', [MikrotikController::class, 'createGroupUser'])->name('admin.mikrotik.CreateGroupUser');
    // Edit Kategori User
    Route::get('/admin/mikrotik/kategori_user/edit/{id}', [MikrotikController::class, 'editCreateGroupUser'])->name('admin.mikrotik.editCreateGroupUser');
    Route::post('/admin/mikrotik/kategori_user/edit', [MikrotikController::class, 'updateGroupUser'])->name('admin.mikrotik.updateGroupUser');
    // Disable Kategori User


    // Show Pengguna Hotspot
    Route::get('/admin/mikrotik/activeUser', [MikrotikController::class, 'showUserHotspot'])->name('admin.mikrotik.showActive');
    
    // Show Queue 
    Route::get('/admin/mikrotik/queue', [MikrotikController::class, 'getListQueue'])->name('admin.mikrotik.getQueue');

    // Show Hotspot Control
    Route::get('/admin/mikrotik/hotspot', [MikrotikController::class, 'getHotspot'])->name('admin.mikrotik.getHotspot');
});
