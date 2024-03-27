<?php

namespace App\Http\Controllers;

use App\Mail\DeliveryConfirmation;
use App\Models\Cart;
use App\Models\Cashier;
use App\Models\Orders;
use App\Models\Purchase;
use App\Models\Sales;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        // Find the cart
        $cart = Cart::findOrFail($cartId);
    
        // Check if the cart has an associated order
        if (!$cart->orders()->exists()) {
            return redirect()->back()->with('error', 'Failed to send delivery confirmation. Order not found for the cart.');
        }
    
        // Get the first order associated with the cart
        $order = $cart->orders()->first();
    
        // Send email to the user
        $user = $cart->user;
        $userEmail = $user->email;
    
        // Send email using Laravel Mail facade and pass both $cart and $order variables to the email template
        Mail::to($userEmail)->send(new DeliveryConfirmation($cart, $order)); // Pass $cart and $order variables 
        
        $cart->update(['delivery_status' => 2]);
    
        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Delivery status updated successfully and email dispatched.');
    }
    
    public function pending()
    {
        $orders = Orders::all();
    
        // Pass the data to the view and return it
        return view('Features.pending', compact('orders'));
    }

    public function stats()
    {
        try {
            // Define sales data manually for each year
            $salesData = [];
            $startYear = 2022;
            $endYear = 2035;

            // Initial sales value
            $initialSales = 15000;

            // Calculate projected sales based on a growth rate
            $growthRate = 0.05;
            for ($year = $startYear; $year <= $endYear; $year++) {
                $salesData[] = [
                    'Year' => $year,
                    'Sales' => $initialSales,
                    'Projected_Sales' => $initialSales * (1 + $growthRate) ** ($year - $startYear)
                ];

                // Update initial sales for the next year
                $initialSales = $salesData[count($salesData) - 1]['Projected_Sales'];
            }

            // Return the view with the sales data
            return view('Features.stats')->with('salesData', $salesData);
        } catch (Exception $e) {
            Log::error('Failed to generate sales data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate sales data: ' . $e->getMessage()], 500);
        }
    }

    public function online()
    {
        $orders = Orders::all();
    
        // Pass the data to the view and return it
        return view('Features.onlinesales', compact('orders'));
    }

    public function onsite()
    {
        // Retrieve all cashiers
        $cashiers = Cashier::all();
        
        // Retrieve all purchase IDs from the cashiers table
        $purchaseIds = $cashiers->pluck('purchase_id');
        
        // Retrieve all item images from the purchases table using the purchase IDs
        $itemImages = Purchase::whereIn('id', $purchaseIds)->pluck('item_image', 'id');
        
        // Pass the data to the view
        return view('Features.onsitesales', compact('cashiers', 'itemImages'));
    }    

}
