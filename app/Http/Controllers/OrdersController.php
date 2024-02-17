<?php

namespace App\Http\Controllers;

use App\Mail\OrderDelivered;
use App\Models\Cart;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        // Find the cart
        $cart = Cart::findOrFail($cartId);
    
        // Check if the cart has an associated order
        if (!$cart->orders()->exists()) {
            return redirect()->back()->with('error', 'Failed to update delivery status. Order not found for the cart.');
        }
    
        // Get the first order associated with the cart
        $order = $cart->orders()->first();
    
        // Send email to yourself
        $adminEmail = 'beantobrew24@gmail.com'; // Replace with your email
        Mail::to($adminEmail)->send(new OrderDelivered($cart, $order)); // Pass $cart and $order variables to the email template
        
        // Update the delivery status
        $cart->update(['delivery_status' => 3]);
    
        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Delivery status updated successfully.');
    }

    public function ratePage($cart_id)
    {
        $cart = Cart::findOrFail($cart_id); // Retrieve the cart item using the provided cart_id
        return view('features.rate', compact('cart'));
    }
}
