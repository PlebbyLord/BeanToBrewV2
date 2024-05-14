<?php

namespace App\Http\Controllers;

use App\Mail\OrderDelivered;
use App\Models\Cart;
use App\Models\Orders;
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
        return view('Features.rate', compact('cart'));
    }

    public function cancelOrder($cartId)
    {
        // Find the cart
        $cart = Cart::findOrFail($cartId);

        // Check if the cart has an associated order
        if (!$cart->orders()->exists()) {
            return redirect()->back()->with('error', 'Failed to cancel order. Order not found for the cart.');
        }

        // Get the associated order
        $order = $cart->orders()->first();

        // If the delivery status is not 'Preparing to Ship' (status 1), do not allow cancellation
        if ($cart->delivery_status != 1) {
            return redirect()->back()->with('error', 'Order cannot be canceled as it is not in "Preparing to Ship" status.');
        }

        // Retrieve the purchase related to the cart
        $purchase = Purchase::findOrFail($cart->purchase_id);

        // Increment the purchase stock by the canceled quantity
        $purchase->update([
            'item_stock' => $purchase->item_stock + $cart->quantity
        ]);

        // Delete the order and associated cart
        $order->delete();
        $cart->delete();

        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Order canceled successfully. Item quantity restored to stock.');
    }

    public function confirmOrder($orderId)
    {
        // Find the order by ID
        $order = Orders::findOrFail($orderId);

        // Check if the order's current confirmation status is 'Preparing' (status 1)
        if ($order->confirmation_status != 1) {
            return redirect()->back()->with('error', 'Order confirmation cannot be updated.');
        }

        // Update the confirmation_status to 'Confirmed' (status 2)
        $order->update(['confirmation_status' => 2]);

        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Order confirmed successfully.');
    }
}
