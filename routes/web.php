<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransferController;


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

Route::get('/', function () {
    return view('auth.login');
});


Auth::routes();



Route::group(['middleware' => ['auth']], function() {
    Route::resource('categories', CategoryController::class);
    Route::resource('posts', PostController::class);
    Route::resource('users', UserController::class);
    Route::resource('role', RoleController::class);   
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/userData', [UserController::class, 'userData'])->name('userData');
    Route::get('/roleData', [RoleController::class, 'roleData'])->name('roleData');
    Route::get('/postData', [PostController::class, 'postData'])->name('postData');
    Route::get('/categoryData', [CategoryController::class, 'categoryData'])->name('categoryData');
    Route::get('/transferData', [TransferController::class, 'transferData'])->name('transferData');
    Route::get('export', [TransferController::class, 'export'])->name('export');
    Route::post('import', [TransferController::class, 'import'])->name('import');
    Route::get('/pdf', [TransferController::class, 'pdf'])->name('pdf');
    Route::resource('transfer', TransferController::class);
});