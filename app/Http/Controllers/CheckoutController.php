<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Checkout;
use App\Models\Dataset;
use App\Models\Orders;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::with('purchase.user')->get();
        $checkoutController = app(CheckoutController::class);

        return view('Features.checkout', compact('carts', 'checkoutController'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Checkout $checkout)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checkout $checkout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Checkout $checkout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checkout $checkout)
    {
        //
    }

    public function placeOrder(Request $request)
    {
        $user_id = auth()->id();

        // Validate the request
        $request->validate([
            'shipping_option' => 'required',
            'payment_option' => 'required',
        ]);

        // Retrieve all items with checkout_status = 1
        $carts = Cart::where('user_id', $user_id)
            ->where('checkout_status', 1)
            ->get();

        // Calculate the total prices sum
        $totalPricesSum = $carts->sum(function ($cart) {
            return $cart->item_price * $cart->quantity;
        });

        // Calculate the total shipping cost
        $totalShippingCost = 0.00;
        try {
            foreach ($request->input('shipping_option') as $selectedValue) {
                if ($selectedValue === '1') {
                    $totalShippingCost += 100.00;
                } elseif ($selectedValue === '2') {
                    $totalShippingCost += 150.00;
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Please select shipping option.');
        }

        // Calculate the total payment
        $totalPayment = $totalPricesSum + $totalShippingCost;

        foreach ($carts as $cart) {
            // Check if there is enough stock before processing the order
            if ($cart->quantity > $cart->purchase->item_stock) {
                // Handle insufficient stock scenario (you can redirect back with an error message)
                return redirect()->back()->with('error', 'Insufficient stock for ' . $cart->purchase->item_name);
            }

            // Save order details to the database for each item in the cart
            $order = new Orders();
            $order->cart_id = $cart->id;
            $order->name = $request->input('name');

            $phoneNumber = $request->input('number');

            if (strlen($phoneNumber) !== 11 || substr($phoneNumber, 0, 2) !== '09') {
                return redirect()->back()->with('error', 'Please use a valid Philippine mobile number with a total length of 11 characters, starting with "09".');
            } else {
                $order->number = $phoneNumber;
            }

            $order->address = $request->input('address');
            $order->shipping_option = $request->input('shipping_option')[0];
            $order->payment_option = $request->input('payment_option')[0];
            $order->total_payment = $totalPayment;
            $order->save();

            // Update item stock for the current item in the cart
            $cart->purchase->decrement('item_stock', $cart->quantity);
        }

        // Update checkout_status for all items in the cart with checkout_status = 1 to 2
        Cart::where('user_id', $user_id)
            ->where('checkout_status', 1)
            ->update(['checkout_status' => 2]);

            // Return the updated cart data
            $updatedCart = Cart::where('user_id', $user_id)->get();

            // save record for dataset model copy
            Dataset::create(['salse_date'=>Carbon::parse($updatedCart->updated_at)->format('y-m-d'), 'coffee_type'=> $updatedCart->item_name, 'coffee_form'=>'','sales_kg'=>$updatedCart->quantity, 'price_per_kilo'=>$updatedCart->item_price]);
    

        // Pass order information and cart details to the view
        return redirect()->route('Features.orders')->with('order', $order)->with('carts', $carts);
    }
}
