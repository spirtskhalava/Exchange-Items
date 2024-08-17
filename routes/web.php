<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
Auth::routes();
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'show']);
Route::get('products/{product}/offer', [ExchangeController::class, 'create'])->name('exchanges.create');
Route::post('products/{product}/offer', [ExchangeController::class, 'store'])->name('exchanges.store');
Route::get('exchanges', [ExchangeController::class, 'index'])->name('exchanges.index');
Route::patch('exchanges/{exchange}', [ExchangeController::class, 'updateStatus'])->name('exchanges.updateStatus');
Route::resource('listings', ListingController::class)->except(['show']);
Route::delete('listing/{product}',  [ListingController::class, 'destroy'])->name('listings.destroy');
Route::get('listing/{id}/edit',[ListingController::class, 'edit']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
Route::delete('exchanges/{exchange}/cancel', [ExchangeController::class, 'cancel'])->name('exchanges.cancel');