<?php

use App\Http\Controllers\MappingController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\VerificationController;

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();
Route::get('/purchase', [App\Http\Controllers\PurchaseController::class, 'index'])->name('features.purchase');
Route::get('/terms', [App\Http\Controllers\HomeController::class, 'terms'])->name('terms');
Route::get('/mapping', [App\Http\Controllers\MappingController::class, 'index'])->name('features.mapping');
Route::get('/viewitem/{id}', [App\Http\Controllers\ViewItemController::class, 'showItem'])->name('viewitem.showItem');
Route::get('/viewitem', [App\Http\Controllers\ViewItemController::class, 'showItem'])->name('viewitem.showItem.query');
Route::get('/verification/form', [App\Http\Controllers\VerificationController::class, 'showVerificationForm'])->name('verification.form');
Route::post('/verify', [VerificationController::class, 'verify'])->name('verification.verify');

Route::middleware('verifyStatus')->group(function () {
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('features.cart');
Route::get('/orders', [App\Http\Controllers\OrdersController::class, 'index'])->name('features.orders');
Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.addToCart');
Route::post('/cart/updateQuantity', [App\Http\Controllers\CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::delete('/cart/{cart}', [App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
Route::post('/place-order', [App\Http\Controllers\CheckoutController::class, 'placeOrder'])->name('place.order');
Route::put('/orders/updateDeliveryStatus/{cartId}', [App\Http\Controllers\OrdersController::class, 'updateDeliveryStatus'])->name('orders.updateDeliveryStatus');
Route::get('/rate/{cart_id}', [App\Http\Controllers\OrdersController::class, 'ratePage'])->name('features.rate');
Route::post('/rate/save', [App\Http\Controllers\RatingController::class, 'save'])->name('rate.save');
Route::get('mapping/getMappingData', [MappingController::class, 'getMappingData'])->name('mapping.getMappingData');
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
Route::post('/update-profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('update.profile');
});

Route::middleware(['checkUserRole'])->group(function () {
    Route::get('/schedule', [App\Http\Controllers\ScheduleController::class, 'index'])->name('features.schedule');
    Route::post('/save-schedule', [App\Http\Controllers\ScheduleController::class, 'saveSchedule'])->name('save-schedule');
    Route::post('/grind-schedule', [App\Http\Controllers\ScheduleController::class, 'GrindingSave'])->name('grind-schedule');
    Route::post('/pack-schedule', [App\Http\Controllers\ScheduleController::class, 'PackagingSave'])->name('pack-schedule');
    Route::post('/roast-schedule', [App\Http\Controllers\ScheduleController::class, 'RoastingSave'])->name('roast-schedule');
    Route::get('/get-batch-numbers', [ScheduleController::class,'getBatchNumbers'])->name('get-batch-numbers');
    Route::get('/harvest', [ScheduleController::class, 'harvest'])->name('harvest');
    Route::get('/history', [ScheduleController::class, 'history'])->name('history');
    Route::post('/sched-start/{id}', [ScheduleController::class, 'schedStart'])->name('schedStart');
    Route::post('/update-progress/{id}', [ScheduleController::class, 'updateProgress'])->name('updateProgress');         
    Route::get('/completed', [ScheduleController::class, 'completed'])->name('completed');
    Route::get('/grind', [ScheduleController::class, 'grind'])->name('grind');
    Route::get('/pack', [ScheduleController::class, 'pack'])->name('pack');
    Route::get('/roast', [ScheduleController::class, 'roast'])->name('roast');
    Route::get('/calendar', [ScheduleController::class, 'calendar'])->name('calendar');
    Route::get('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('features.inventory');
    Route::get('/users', [App\Http\Controllers\UsersController::class, 'index'])->name('features.users');
    Route::get('/users/admins', [App\Http\Controllers\UsersController::class, 'admins'])->name('features.admins');
    Route::get('/cashier', [App\Http\Controllers\CashierController::class, 'index'])->name('features.cashier');
    Route::post('/add-to-temp-cash', [App\Http\Controllers\CashierController::class, 'addToTempCash'])->name('addToTempCash');
    Route::post('/cashier/change-quantity', [App\Http\Controllers\CashierController::class, 'changeQuantity'])->name('cashier.changeQuantity');
    Route::post('/checkout', [App\Http\Controllers\CashierController::class, 'checkout'])->name('cashier.checkout');
    Route::delete('/cashier/remove', [App\Http\Controllers\CashierController::class, 'remove'])->name('cashier.remove');
    Route::post('/admin/store', [App\Http\Controllers\UsersController::class, 'storeAdmin'])->name('storeAdmin');
    Route::get('/sales', [App\Http\Controllers\SalesController::class, 'index'])->name('features.sales');
    Route::get('/sales/online', [App\Http\Controllers\SalesController::class, 'online'])->name('features.onlinesales');
    Route::get('/sales/onsite', [App\Http\Controllers\SalesController::class, 'onsite'])->name('features.onsitesales');
    Route::get('/sales/pending', [App\Http\Controllers\SalesController::class, 'pending'])->name('features.pending');
    Route::get('/sales/stats', [App\Http\Controllers\SalesController::class, 'stats'])->name('features.stats');
    Route::post('/save-item', [App\Http\Controllers\PurchaseController::class, 'saveItem'])->name('save.item');
    Route::post('/deliver/{cartId}', [App\Http\Controllers\SalesController::class, 'DeliverSend'])->name('deliver.send');
    Route::get('/mappingsave', [App\Http\Controllers\MappingController::class, 'mappingsave'])->name('features.mappingsave');
    Route::post('/mapping/save', [App\Http\Controllers\MappingController::class, 'save'])->name('mapping.save');
    Route::get('/transfer', [App\Http\Controllers\PurchaseController::class, 'transferPage'])->name('features.transfer');
    Route::post('/add-to-temp-inv', [App\Http\Controllers\PurchaseController::class, 'addToTempInv'])->name('addToTempInv');
    Route::post('/transfer/change-quantity', [App\Http\Controllers\PurchaseController::class, 'changeQuantity'])->name('transfer.changeQuantity');
    Route::delete('/transfer/remove', [App\Http\Controllers\PurchaseController::class, 'remove'])->name('transfer.remove');
    Route::post('/request', [App\Http\Controllers\PurchaseController::class, 'request'])->name('transfer.request');
    Route::post('/approve-transfer/{purchase}', [App\Http\Controllers\PurchaseController::class,'approveTransferStatus'])->name('approveTransfer');
    Route::post('/reject-transfer/{purchase}', [App\Http\Controllers\PurchaseController::class,'rejectTransferStatus'])->name('rejectTransfer');
    Route::post('/mark-received/{purchase}', [App\Http\Controllers\PurchaseController::class, 'markReceived'])->name('markReceived');
    Route::put('/comments/{id}/hide', [App\Http\Controllers\RatingController::class, 'hide'])->name('comments.hide');
    Route::get('/export-onsite-sales', [SalesController::class, 'exportonsite'])->name('export.onsite.sales');
    Route::get('/export/online-sales', [SalesController::class, 'exportOnlineSales'])->name('export.online.sales');

});
