<?php

use App\Http\Controllers\MappingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;

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
Route::get('/mapping', [App\Http\Controllers\MappingController::class, 'index'])->name('features.mapping');
Route::get('/viewitem/{id}', [App\Http\Controllers\ViewItemController::class, 'showItem'])->name('viewitem.showItem');
Route::get('/viewitem', [App\Http\Controllers\ViewItemController::class, 'showItem'])->name('viewitem.showItem.query');
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

Route::middleware(['checkUserRole'])->group(function () {
    Route::get('/schedule', [App\Http\Controllers\ScheduleController::class, 'index'])->name('features.schedule');
    Route::post('/plant-schedule', [App\Http\Controllers\ScheduleController::class, 'PlantingSave'])->name('plant-schedule');
    Route::post('/dry-schedule', [App\Http\Controllers\ScheduleController::class, 'DryingSave'])->name('dry-schedule');
    Route::post('/ferment-schedule', [App\Http\Controllers\ScheduleController::class, 'FermentingSave'])->name('ferment-schedule');
    Route::post('/grind-schedule', [App\Http\Controllers\ScheduleController::class, 'GrindingSave'])->name('grind-schedule');
    Route::post('/hull-schedule', [App\Http\Controllers\ScheduleController::class, 'HullingSave'])->name('hull-schedule');
    Route::post('/pack-schedule', [App\Http\Controllers\ScheduleController::class, 'PackagingSave'])->name('pack-schedule');
    Route::post('/pulp-schedule', [App\Http\Controllers\ScheduleController::class, 'PulpingSave'])->name('pulp-schedule');
    Route::post('/roast-schedule', [App\Http\Controllers\ScheduleController::class, 'RoastingSave'])->name('roast-schedule');
    Route::post('/sort-schedule', [App\Http\Controllers\ScheduleController::class, 'SortingSave'])->name('sort-schedule');
    Route::post('/prune-schedule', [App\Http\Controllers\ScheduleController::class, 'PruneSave'])->name('prune-schedule');
    Route::post('/pesticide-schedule', [App\Http\Controllers\ScheduleController::class, 'PesticideSave'])->name('pesticide-schedule');
    Route::get('/get-batch-numbers', [ScheduleController::class,'getBatchNumbers'])->name('get-batch-numbers');
    Route::get('/harvest', [ScheduleController::class, 'harvest'])->name('harvest');
    Route::get('/history', [ScheduleController::class, 'history'])->name('history');
    Route::get('/pesticide', [ScheduleController::class, 'pesticide'])->name('pesticide');
    Route::get('/prune', [ScheduleController::class, 'prune'])->name('prune');
    Route::get('/dry', [ScheduleController::class, 'dry'])->name('dry');
    Route::get('/ferment', [ScheduleController::class, 'ferment'])->name('ferment');
    Route::get('/grind', [ScheduleController::class, 'grind'])->name('grind');
    Route::get('/hull', [ScheduleController::class, 'hull'])->name('hull');
    Route::get('/pack', [ScheduleController::class, 'pack'])->name('pack');
    Route::get('/pulp', [ScheduleController::class, 'pulp'])->name('pulp');
    Route::get('/roast', [ScheduleController::class, 'roast'])->name('roast');
    Route::get('/sort', [ScheduleController::class, 'sort'])->name('sort');
    Route::get('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('features.inventory');
    Route::get('/sales', [App\Http\Controllers\SalesController::class, 'index'])->name('features.sales');
    Route::get('/sales/pending', [App\Http\Controllers\SalesController::class, 'pending'])->name('features.pending');
    Route::post('/save-item', [App\Http\Controllers\PurchaseController::class, 'saveItem'])->name('save.item');
    Route::post('/deliver/{cartId}', [App\Http\Controllers\SalesController::class, 'DeliverSend'])->name('deliver.send');
    Route::get('/mappingsave', [App\Http\Controllers\MappingController::class, 'mappingsave'])->name('features.mappingsave');
    Route::post('/mapping/save', [App\Http\Controllers\MappingController::class, 'save'])->name('mapping.save');
    Route::get('/transfer/{purchase_id}', [App\Http\Controllers\PurchaseController::class, 'transferPage'])->name('features.transfer');
    Route::post('/transfer/item', [App\Http\Controllers\PurchaseController::class, 'transferItem'])->name('transfer.item');


});
