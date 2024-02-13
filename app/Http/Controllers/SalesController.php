<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Orders;
use App\Models\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Orders::all();
    
        // Pass the data to the view and return it
        return view('Features.sales', compact('orders'));
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
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }

    public function DeliverSend(Request $request, $cartId)
    {
        // Find the cart and update the delivery status
        Cart::where('id', $cartId)
            ->where('delivery_status', 1)
            ->update(['delivery_status' => 2]);
                
        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Delivery status updated successfully.');
    }

    public function pending()
    {
        $orders = Orders::all();
    
        // Pass the data to the view and return it
        return view('Features.pending', compact('orders'));
    }
}
