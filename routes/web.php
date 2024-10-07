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

Route::get('/', [SignatureController::class, 'main'])->name('main');
Route::get('/control', [SignatureController::class, 'index'])->name('index');
Route::post('/save-signature', [SignatureController::class, 'store']);
Route::get('/signatures', [SignatureController::class, 'index'])->name('signatures');
Route::get('/signatures/download', [SignatureController::class, 'downloadPdf'])->name('signatures.downloadPdf');


