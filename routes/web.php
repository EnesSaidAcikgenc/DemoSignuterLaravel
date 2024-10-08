<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\SignatureController;

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



//Route::get('/', function () {
//    return view('pdfProject.main');
//});

Route::get('/', [SignatureController::class, 'main'])->middleware('auth')->name('main');
Route::get('/control', [SignatureController::class, 'index'])->name('index');
Route::post('/save-signature', [SignatureController::class, 'store'])->middleware('auth')->name('save-signature');
Route::get('/signatures', [SignatureController::class, 'index'])->name('signatures');
Route::get('/signatures/download', [SignatureController::class, 'downloadPdf'])->name('signatures.downloadPdf');

Route::get('/login', [SignatureController::class, 'login'])->name('login');
Route::post('/loginp', [SignatureController::class, 'loginp'])->name('admin.login');
Route::get('/register', [SignatureController::class, 'register'])->name('register');
Route::post('/registerp', [SignatureController::class, 'registerp'])->name('admin.register');

