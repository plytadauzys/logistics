<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ExpeditionController;
use \App\Http\Controllers\ClientController;
use \App\Http\Controllers\SupplierController;
use \App\Http\Controllers\ManagerController;
use \App\Http\Controllers\AdministratorController;
use \App\Http\Controllers\ExpeditionHistoryController;

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
// Home -------------------------------------------------------------
Route::get('/', function () {
    if(session()->has('admin'))
        return Redirect::to('adminHome');
    else if (session()->has('user'))
        return Redirect::to('managerHome');
    return view('home', ['data' => '']);
});
Route::get('w', function () {
    return view('welcome');
});
Route::get('ldm', function () {
    return view('ldm');
});
//-------------------------------------------------------------------
// Administrators ---------------------------------------------------
Route::post('loginAdmin', [AdministratorController::class, 'login']);
Route::get('logoutAdmin', [AdministratorController::class, 'logout']);
Route::get('adminHome', [AdministratorController::class, 'index']);
Route::post('adminHome/new', [AdministratorController::class, 'createUser']);
Route::post('admin/edit', [AdministratorController::class, 'editUser']);
Route::get('admin/remove/{id}', [AdministratorController::class, 'removeUser']);
//-------------------------------------------------------------------
// Managers----------------------------------------------------------
Route::post('loginManager', [ManagerController::class, 'login']);
Route::get('logoutManager', [ManagerController::class, 'logout']);
Route::get('managerHome', [ManagerController::class, 'index']);
//-------------------------------------------------------------------
// Clients ----------------------------------------------------------
Route::get('clients',[ClientController::class, 'index']);
Route::post('clients/new',[ClientController::class, 'createClient']);
Route::post('clients/edit', [ClientController::class, 'editClient']);
Route::get('searchForClient/{string}', [ClientController::class, 'searchForClient']);
Route::get('clients/remove/{id}', [ClientController::class, 'removeClient']);
//-------------------------------------------------------------------
// Suppliers --------------------------------------------------------
Route::get('suppliers', [SupplierController::class, 'index']);
Route::post('suppliers/new', [SupplierController::class, 'createSupplier']);
Route::post('suppliers/edit', [SupplierController::class, 'editSupplier']);
Route::get('suppliers/remove/{id}', [SupplierController::class, 'removeSupplier']);
//-------------------------------------------------------------------
// Expeditions ------------------------------------------------------
Route::get('/expeditions',[ExpeditionController::class, 'index']);
Route::post('expeditions/new', [ExpeditionController::class, 'createExpedition']);
Route::post('expeditions/file', [ExpeditionController::class, 'importData']);
Route::post('expeditions/changeState', [ExpeditionController::class, 'changeState']);
//-------------------------------------------------------------------
// Expeditions History ----------------------------------------------
Route::get('/expeditionHistory', [ExpeditionHistoryController::class, 'index']);
//-------------------------------------------------------------------
