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
//Route::get('w', function () { return view('welcome'); });
Route::post('loginAdmin', [AdministratorController::class, 'login']);
Route::post('loginManager', [ManagerController::class, 'login']);
/*if(!session()->has('admin') && !session()->has('user'))
    Route::any('{any}', function() {
        return Redirect::to('/');
    })->where('any', '^(?!api).*$');
else {*/
    //-------------------------------------------------------------------
    // Administrators ---------------------------------------------------
    Route::get('logoutAdmin', [AdministratorController::class, 'logout']);
    Route::get('adminHome', [AdministratorController::class, 'index']);
    Route::post('adminHome/new', [AdministratorController::class, 'createUser']);
    Route::post('admin/editUser', [AdministratorController::class, 'editUser']);
    Route::post('admin/editAdmin', [AdministratorController::class, 'editAdmin']);
    Route::get('admin/remove/{id}', [AdministratorController::class, 'removeUser']);
    Route::get('admin/removeAdmin/{id}', [AdministratorController::class, 'removeAdmin']);
    //-------------------------------------------------------------------
    // Managers----------------------------------------------------------
    Route::get('logoutManager', [ManagerController::class, 'logout']);
    Route::get('managerHome', [ManagerController::class, 'index']);
    //-------------------------------------------------------------------
    // Clients ----------------------------------------------------------
    Route::get('clients',[ClientController::class, 'index']);
    Route::post('clients/new',[ClientController::class, 'createClient']);
    Route::post('clients/edit', [ClientController::class, 'editClient']);
    Route::get('searchForClient/{string}', [ClientController::class, 'searchForClient']);
    Route::get('clients/remove/{id}', [ClientController::class, 'removeClient']);
    Route::get('clients/{id}', function ($id) {
        session()->push('client', $id);
        return \redirect()->action([ClientController::class, 'index']);
    });
    //-------------------------------------------------------------------
    // Suppliers --------------------------------------------------------
    Route::get('suppliers', [SupplierController::class, 'index']);
    Route::post('suppliers/new', [SupplierController::class, 'createSupplier']);
    Route::post('suppliers/edit', [SupplierController::class, 'editSupplier']);
    Route::get('suppliers/remove/{id}', [SupplierController::class, 'removeSupplier']);
    Route::get('suppliers/{id}', function ($id) {
        session()->push('supplier', $id);
        return \redirect()->action([SupplierController::class, 'index']);
    });
    //-------------------------------------------------------------------
    // Expeditions ------------------------------------------------------
    Route::get('/expeditions',[ExpeditionController::class, 'index']);
    Route::post('expeditions/new', [ExpeditionController::class, 'createExpedition']);
    Route::post('expeditions/file', [ExpeditionController::class, 'importData']);
    Route::post('expeditions/changeState', [ExpeditionController::class, 'changeState']);
    Route::post('expeditions/edit', [ExpeditionController::class, 'edit']);
    Route::get('expeditions/{id}', function ($id) {
        session()->push('exp', $id);
        return \redirect()->action([ExpeditionController::class, 'index']);
    });
    Route::get('ldm', function () {
        return view('ldm');
    });
    //-------------------------------------------------------------------
    // Expeditions History ----------------------------------------------
    Route::get('/expeditionHistory', [ExpeditionHistoryController::class, 'index']);
    //-------------------------------------------------------------------
//}
