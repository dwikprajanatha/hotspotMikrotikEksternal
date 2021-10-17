<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Call Controller
use App\Http\Controllers\API\MikrotikController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:api')->group(function (){

//     Route::get('check/bandwidth',[MikrotikController::class, 'checkBandwidth']);

// });

Route::get('user/checkUser',[LoginController::class, 'getUsername']);


// Route::group(['middleware' => 'auth:api'], function() {

    //Checking Mikrotik API
    Route::get('check/bandwidth',[MikrotikController::class, 'checkBandwidth']);
    Route::get('check/health', [MikrotikController::class, 'checkHealth']);
    Route::get('check/userActive', [MikrotikController::class, 'checkActiveUserHotspot']);


    //User Management API
    Route::get('user/show', [MikrotikController::class, 'showUserHotspot']);

    //Queue Mangement API
    Route::get('check/queue', [MikrotikController::class, 'showQueue']);

    //Report
    Route::get('report/pertumbuhan/{range}/{tgl}', [AdminController::class, 'apiDataPertumbuhanPengguna']);
    Route::get('report/pengguna/{range}/{tgl}', [AdminController::class, 'apiDataPenggunaan']);
    Route::get('report/platform/{range}/{tgl?}', [AdminController::class, 'apiProporsiPlatform']);
    Route::get('report/umur/{range}/{tgl?}', [AdminController::class, 'apiProporsiUmur']);
    Route::get('report/penggunaan/{range}/{tgl}', [AdminController::class, 'apiPenggunaanPerUser']);

// });