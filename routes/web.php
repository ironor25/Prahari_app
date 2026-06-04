
<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaseCategoryController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\ChallanController;
use App\Http\Controllers\PrahariController;
use App\Http\Controllers\TransactionsController;




Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('signin', [AuthController::class, 'signin'])->name('signin');
Route::post('verify', [AuthController::class, 'verifyLoginCredentials'])->name('verifyLoginCredentials');
Route::get('signup', [AuthController::class, 'signup'])->name('signup');
Route::post('store', [AuthController::class, 'store'])->name('store');

Route::group(['prefix' => 'account','middleware' => 'auth'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
   
    Route::group(['prefix'=>'admin','as'=>'admin.'],function(){
        Route::get('/dashboard',[AdminController::class,'adminDashboard'])->name('dashboard');
        Route::get('/reports',[AdminController::class,'reports'])->name('reports');
        Route::get('/settings',[AdminController::class,'settings'])->name('settings');
        Route::get('/admins', [AdminController::class, 'admins'])->name('admins');
        Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('admins.store');
        Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
        
        // Cases Group
        Route::group(['prefix' => 'cases', 'as' => 'cases.'], function () {
            Route::get('/', [CaseController::class, 'index'])->name('index');
            Route::post('/', [CaseController::class, 'store'])->name('store');
            Route::get('/{id}', [CaseController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [CaseController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CaseController::class, 'update'])->name('update');
            Route::delete('/{id}', [CaseController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/status', [CaseController::class, 'updateStatus'])->name('updateStatus');
        });

        // Challans Group
        Route::group(['prefix' => 'challans', 'as' => 'challans.'], function () {
            Route::get('/', [ChallanController::class, 'index'])->name('index');
            Route::post('/', [ChallanController::class, 'store'])->name('store');
            Route::get('/{id}', [ChallanController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ChallanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ChallanController::class, 'update'])->name('update');
            Route::delete('/{id}', [ChallanController::class, 'destroy'])->name('destroy');
        });

        // Praharis Group
        Route::group(['prefix' => 'praharis', 'as' => 'praharis.'], function () {
            Route::get('/', [PrahariController::class, 'index'])->name('index');
            Route::post('/', [PrahariController::class, 'store'])->name('store');
            Route::get('/{id}', [PrahariController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PrahariController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PrahariController::class, 'update'])->name('update');
            Route::delete('/{id}', [PrahariController::class, 'destroy'])->name('destroy');
        });

        // Transactions Group
        Route::group(['prefix' => 'transactions', 'as' => 'transactions.'], function () {
            Route::get('/', [TransactionsController::class, 'index'])->name('index');
            Route::post('/', [TransactionsController::class, 'store'])->name('store');
            Route::get('/{id}', [TransactionsController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [TransactionsController::class, 'edit'])->name('edit');
            Route::put('/{id}', [TransactionsController::class, 'update'])->name('update');
            Route::delete('/{id}', [TransactionsController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/status', [TransactionsController::class, 'updateStatus'])->name('updateStatus');
        });

        // Case Categories Group
        Route::group(['prefix' => 'case-categories', 'as' => 'case_categories.'], function () {
            Route::get('/', [CaseCategoryController::class, 'index'])->name('index');
            Route::get('/{id}', [CaseCategoryController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [CaseCategoryController::class, 'edit'])->name('edit');
            Route::post('/{id}/update', [CaseCategoryController::class, 'updateAjax'])->name('updateAjax');
        });
    });

});
