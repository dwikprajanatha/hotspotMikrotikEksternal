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

// Login Test
Route::get('/logintest', [LoginController::class, 'loginTest']);

Route::get('user/statusHotspot', [LoginController::class, 'redirectStatus'])->name('hotspot.redirect.status');
Route::post('user/statusHotspot', [LoginController::class, 'status'])->name('hotspot.status');

Route::get('/forgotPassword', [LoginController::class, 'showforgotPassword'])->name('hotspot.forgot.view');
Route::post('/forgotPassword', [LoginController::class, 'forgotPassword'])->name('hotspot.forgot');

Route::get('/forgotUsername', [LoginController::class, 'showForgetUsername'])->name('hotspot.forgot.username');
Route::post('/forgotUsername', [LoginController::class, 'findUsername'])->name('hotspot.forgot.username.post');

Route::get('/daftar', [LoginController::class, 'create'])->name('hotspot.register.view');
Route::post('/daftar', [LoginController::class, 'daftar'])->name('hotspot.register');

Route::get('/keluhan', [LoginController::class, 'createKeluhan'])->name('hotspot.keluhan.view');
Route::post('/post/keluhan', [LoginController::class, 'postKeluhan'])->name('hotspot.keluhan.post');

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

// Rejected Hotspot User Organik Form Resubmit 
Route::get('/user/resubmit/{tokenID}', [LoginController::class, 'resubmitForm'])->name('user.resubmit.form');
Route::post('/user/resubmit/post', [LoginController::class, 'resubmit'])->name('user.resubmit.post');



Route::get('test',  [LoginController::class, 'test']);
/**
 * 
 * ADMIN START HERE
 * 
 */

Route::group(['middleware' => 'auth:web'], function() {
    
    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // List Pengumuman
    Route::get('/admin/pengumuman', [AdminController::class, 'listPengumuman'])->name('admin.pengumuman');

    // Create Pengumuman
    Route::get('/admin/pengumuman/create', [AdminController::class, 'createPengumuman'])->name('admin.pengumuman.create.view');
    Route::post('/admin/pengumuman/create', [AdminController::class, 'postPengumuman'])->name('admin.pengumuman.create');
    
    // Edit Pengumuman
    Route::get('/admin/pengumuman/edit/{id}', [AdminController::class, 'editPengumuman'])->name('admin.pengumuman.edit');
    Route::post('/admin/pengumuman/update', [AdminController::class, 'updatePengumuman'])->name('admin.pengumuman.update');

    // Disable Pengumuman
    Route::get('/admin/pengumuman/disable/{id}', [AdminController::class, 'disablePengumuman'])->name('admin.pengumuman.disable');

    // Disable Gambar Pengumuman
    Route::get('/admin/pengumuman/pic/disable/{id}', [AdminController::class, 'disableFile'])->name('admin.pengumuman.pic.disable');

    // Enable Pengumuman
    Route::get('/admin/pengumuman/enable/{id}', [AdminController::class, 'enablePengumuman'])->name('admin.pengumuman.enable');
    
    // Enable Gambar Pengumuman 
    Route::get('/admin/pengumuman/pic/enable/{id}', [AdminController::class, 'enableFile'])->name('admin.pengumuman.pic.enable');

    // Keluhan
    Route::get('/admin/keluhan', [AdminController::class, 'listKeluhan'])->name('admin.listKeluhan');
    Route::get('/admin/keluhan/{id}', [AdminController::class, 'readKeluhan'])->name('admin.readKeluhan');
    Route::get('/admin/keluhan/delete/{id}', [AdminController::class, 'deleteKeluhan'])->name('admin.deleteKeluhan');

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
    
    // Update Hotspot User
    Route::post('/admin/hotspot/update', [AdminController::class, 'updateUser'])->name('admin.user.update');

    // Disable Hotspot User
    Route::get('/admin/hotspot/{user}/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');

    // Enable Hotspot User
    Route::get('/admin/hotspot/{user}/enable/{id}', [AdminController::class, 'enableUser'])->name('admin.user.enable');
    
    // Add Custom Rules
    Route::post('/admin/hotspot/custom_rules/add', [AdminController::class, 'addCustomRules'])->name('admin.custom.rules.add');

    // Delete Custom Rules
    Route::get('/admin/hotspot/custom_rules/{user}/delete/{id}', [AdminController::class, 'disableCustomRules'])->name('admin.custom.rules.disable');
    
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

    // List Load Balancing
    Route::get('/admin/mikrotik/loadBalancing', [MikrotikController::class, 'listLoadBalancing'])->name('admin.mikrotik.listLoadBalancing');
    // Form Load Balancing
    Route::get('/admin/mikrotik/loadBalancing/create', [MikrotikController::class, 'showLoadBalancing'])->name('admin.mikrotik.show.loadBalancing');
    // Create Load Balancing
    Route::post('/admin/mikrotik/loadBalancing/create', [MikrotikController::class, 'createLoadBalancing'])->name('admin.mikrotik.post.loadBalancing');
    // Edit Load Balancing
    Route::get('/admin/mikrotik/loadBalancing/edit/{id}', [MikrotikController::class, 'editLoadBalancing'])->name('admin.mikrotik.edit.loadBalancing');
    // Update Load Balancing
    Route::post('/admin/mikrotik/loadBalancing/update', [MikrotikController::class, 'createLoadBalancing'])->name('admin.mikrotik.update.loadBalancing');
    // Disable Load Balancing
    Route::get('/admin/mikrotik/loadBalancing/disable/{id}', [MikrotikController::class, 'disableLoadBalancing'])->name('admin.mikrotik.disable.loadBalancing');
    // Enable Load Balancing
    Route::get('/admin/mikrotik/loadBalancing/enable/{id}', [MikrotikController::class, 'enableLoadBalancing'])->name('admin.mikrotik.enable.loadBalancing');

});
