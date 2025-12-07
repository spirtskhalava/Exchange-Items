<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserReviewController; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'show']);
Route::get('products/{product}/offer', [ExchangeController::class, 'create'])->name('exchanges.create');
Route::post('products/{product}/offer', [ExchangeController::class, 'store'])->name('exchanges.store');
Route::put('products/{product}', [ListingController::class, 'update'])->name('products.update');
Route::get('exchanges', [ExchangeController::class, 'index'])->name('exchanges.index');
Route::patch('exchanges/{exchange}', [ExchangeController::class, 'updateStatus'])->name('exchanges.updateStatus');
Route::resource('listings', ListingController::class)->except(['show']);
Route::delete('listing/{product}',  [ListingController::class, 'destroy'])->name('listings.destroy');
Route::get('listing/{id}/edit',[ListingController::class, 'edit']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
Route::delete('exchanges/{exchange}/cancel', [ExchangeController::class, 'cancel'])->name('exchanges.cancel');
Route::post('products/{id}/remove-image', [ProductController::class, 'removeImage'])->name('products.removeImage');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
Route::get('/seller/{id}/items', [ProductController::class, 'showSellerItems'])->name('seller.items');
Route::get('/migrate-now', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migrations executed!';
});

Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/store', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/fetch', [MessageController::class, 'fetchMessages'])->name('messages.fetch');
    Route::get('/messages/open/{sellerId}', [MessageController::class, 'openChatWithSeller'])->name('messages.openChatWithSeller');
    Route::post('/messages/mark-read', [MessageController::class, 'markAsRead'])->name('messages.markRead');
    Route::post('/products/{id}/review', [App\Http\Controllers\ProductController::class, 'storeReview'])->name('products.review.store');
    Route::post('/users/{id}/review', [UserReviewController::class, 'store'])->name('user.reviews.store');
});