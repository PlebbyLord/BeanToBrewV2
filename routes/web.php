<?php

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

Route::middleware(['checkUserRole'])->group(function () {
    Route::get('/schedule', [App\Http\Controllers\ScheduleController::class, 'index'])->name('features.schedule');
    Route::post('/plant-schedule', [App\Http\Controllers\ScheduleController::class, 'PlantingSave'])->name('plant-schedule');
    Route::post('/water-schedule', [App\Http\Controllers\ScheduleController::class, 'WaterSave'])->name('water-schedule');
    Route::post('/prune-schedule', [App\Http\Controllers\ScheduleController::class, 'PruneSave'])->name('prune-schedule');
    Route::post('/pesticide-schedule', [App\Http\Controllers\ScheduleController::class, 'PesticideSave'])->name('pesticide-schedule');
    Route::get('/get-batch-numbers', [ScheduleController::class,'getBatchNumbers'])->name('get-batch-numbers');
    Route::get('/harvest', [ScheduleController::class, 'harvest'])->name('harvest');
    Route::get('/history', [ScheduleController::class, 'history'])->name('history');
    Route::get('/pesticide', [ScheduleController::class, 'pesticide'])->name('pesticide');
    Route::get('/prune', [ScheduleController::class, 'prune'])->name('prune');
    Route::get('/water', [ScheduleController::class, 'water'])->name('water');
    Route::get('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('features.inventory');
    Route::get('/sales', [App\Http\Controllers\SalesController::class, 'index'])->name('features.sales');
    Route::get('/sales/pending', [App\Http\Controllers\SalesController::class, 'pending'])->name('features.pending');
    Route::post('/save-item', [App\Http\Controllers\PurchaseController::class, 'saveItem'])->name('save.item');
    Route::delete('/purchase/{id}', [App\Http\Controllers\PurchaseController::class, 'destroy'])->name('purchase.delete');
    Route::post('/deliver/{cartId}', [App\Http\Controllers\SalesController::class, 'DeliverSend'])->name('deliver.send');

});
