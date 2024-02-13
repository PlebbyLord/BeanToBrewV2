<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $carts = Cart::with('purchase.user')->get();
        
        return view('Features.orders', compact('carts'));
    }

        public function updateDeliveryStatus(Request $request, $cartId)
    {
        // Find the cart and update the delivery status
        Cart::where('id', $cartId)
            ->where('delivery_status', 2)
            ->update(['delivery_status' => 3]);
                
        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Delivery status updated successfully.');
    }
}
