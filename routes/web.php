<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProductVerificationController;
use App\Http\Controllers\SavedSearchController;
use App\Http\Controllers\Auth\SocialAuthController;

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

// Google OAuth
Route::get('/auth/google',          [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/trade-protection', fn() => view('pages.trade-protection'))->name('trade.protection');
Route::get('/terms',            fn() => view('pages.terms'))->name('terms');
Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'show']);
Route::get('products/{product}/offer', [ExchangeController::class, 'create'])->name('exchanges.create');
Route::post('products/{product}/offer', [ExchangeController::class, 'store'])->name('exchanges.store');
Route::put('products/{product}', [ListingController::class, 'update'])->name('products.update');
Route::get('exchanges', [ExchangeController::class, 'index'])->name('exchanges.index');
Route::patch('exchanges/{exchange}', [ExchangeController::class, 'updateStatus'])->name('exchanges.updateStatus');
Route::resource('listings', ListingController::class)->except(['show']);
Route::get('listing/{id}/edit',[ListingController::class, 'edit']);
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

Route::post('/products/{product}/verify', [ProductVerificationController::class, 'verify'])
    ->name('products.verify')
    ->middleware('auth');


Route::middleware(['auth'])->group(function () {
    Route::get('/trades', [TradeController::class, 'index'])->name('trades.index');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
});

Route::middleware(['auth'])->group(function () {
    // Notifications
    Route::post('/notifications/{id}/read', function ($id) {
        Auth::user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    })->name('notifications.read');

    Route::post('/notifications/read-all', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');
});

Route::middleware(['auth'])->group(function () {
    // Insurance
    Route::post('exchanges/{exchange}/insurance/opt-in',   [InsuranceController::class, 'optIn'])->name('insurance.optIn');
    Route::post('exchanges/{exchange}/insurance/valuation',[InsuranceController::class, 'submitValuation'])->name('insurance.submitValuation');
    Route::post('exchanges/{exchange}/insurance/respond',  [InsuranceController::class, 'respondValuation'])->name('insurance.respondValuation');
    Route::get('exchanges/{exchange}/insurance/pay',       [InsuranceController::class, 'createPayment'])->name('insurance.pay');
    Route::get('exchanges/{exchange}/insurance/success',   [InsuranceController::class, 'paymentSuccess'])->name('insurance.paymentSuccess');
    Route::post('exchanges/{exchange}/insurance/received', [InsuranceController::class, 'markReceived'])->name('insurance.markReceived');

    // Disputes
    Route::get('exchanges/{exchange}/dispute',  [DisputeController::class, 'create'])->name('disputes.create');
    Route::post('exchanges/{exchange}/dispute', [DisputeController::class, 'store'])->name('disputes.store');
    // Legacy redirect for old dispute routes
    Route::get('admin/disputes',            fn() => redirect()->route('admin.disputes.index'))->name('admin.disputes.index');
    Route::post('admin/disputes/{dispute}/resolve', [DisputeController::class, 'adminResolve'])->name('admin.disputes.resolve');

});

// ── Admin Panel ────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                                    [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',                               [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle-status',         [AdminController::class, 'toggleUserStatus'])->name('users.toggleStatus');
    Route::post('/users/{user}/toggle-admin',          [AdminController::class, 'toggleAdminRole'])->name('users.toggleAdmin');
    Route::put('/users/{user}',                        [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}',                     [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/products',                            [AdminController::class, 'products'])->name('products');
    Route::post('/products/{product}/toggle',          [AdminController::class, 'toggleProduct'])->name('products.toggle');
    Route::put('/products/{product}',                  [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}',               [AdminController::class, 'deleteProduct'])->name('products.delete');
    Route::get('/finances',                            [AdminController::class, 'finances'])->name('finances');
    Route::get('/disputes',                            [DisputeController::class, 'adminIndex'])->name('disputes.index');
    Route::post('/disputes/{dispute}/resolve',         [DisputeController::class, 'adminResolve'])->name('disputes.resolve');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/store', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/fetch', [MessageController::class, 'fetchMessages'])->name('messages.fetch');
    Route::get('/messages/open/{sellerId}', [MessageController::class, 'openChatWithSeller'])->name('messages.openChatWithSeller');
    Route::post('/messages/mark-read', [MessageController::class, 'markAsRead'])->name('messages.markRead');
    Route::get('/messages/search-users', [MessageController::class, 'searchUsers'])->name('messages.searchUsers');
    Route::post('/products/{id}/review', [App\Http\Controllers\ProductController::class, 'storeReview'])->name('products.review.store');
    Route::post('/users/{id}/review', [UserReviewController::class, 'store'])->name('user.reviews.store');

    // Saved searches
    Route::post('/saved-searches',                   [SavedSearchController::class, 'store'])->name('saved-searches.store');
    Route::delete('/saved-searches/{savedSearch}',   [SavedSearchController::class, 'destroy'])->name('saved-searches.destroy');
    Route::get('/saved-searches',                    [SavedSearchController::class, 'index'])->name('saved-searches.index');
});