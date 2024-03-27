<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use App\Models\Purchase;
use App\Models\TempCash;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if filter parameters are provided in the request
        if ($request->filled('coffee_type')) {
            // Validate the request
            $request->validate([
                'coffee_type' => 'required|array', // Ensure coffee_type is an array
                'coffee_type.*' => 'in:green,roasted,grinded', // Validate each coffee type
            ]);
            
            // Extract the selected coffee types from the request
            $selectedCoffeeTypes = $request->input('coffee_type');
    
            // Query the purchases table based on the selected coffee types
            $purchases = Purchase::whereIn('coffee_type', $selectedCoffeeTypes)
                ->where('branch', auth()->user()->branch)
                ->get();
        } else {
            // No filter parameters provided, so get all items for the user's branch
            $purchases = Purchase::where('branch', auth()->user()->branch)->get();
        }
    
        // Retrieve items currently in the temp_cashes table
        $tempCashes = TempCash::where('user_id', auth()->id())->get();
    
        // Pass the filtered or unfiltered purchases data and temp cash items to the view
        return view('features.cashier', ['purchases' => $purchases, 'tempCashes' => $tempCashes]);
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
    public function show(Cashier $cashier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cashier $cashier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cashier $cashier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cashier $cashier)
    {
        //
    }

    public function addToTempCash(Request $request)
    {
        // Validate the request data
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'item_name' => 'required|string',
            'item_image' => 'required|string',
            'item_price' => 'required|numeric',
        ]);
    
        // Check if the item already exists in the temp_cashes table
        $existingTempCash = TempCash::where('purchase_id', $request->purchase_id)
            ->where('user_id', auth()->id())
            ->first();
    
        if ($existingTempCash) {
            // Increment the quantity by 1
            $existingTempCash->increment('quantity', 1);
        } else {
            // Create a new record in the temp_cashes table
            TempCash::create([
                'user_id' => auth()->id(),
                'purchase_id' => $request->purchase_id,
                'item_name' => $request->item_name,
                'item_image' => $request->item_image,
                'item_price' => $request->item_price,
                'quantity' => 1, // Default quantity
            ]);
        }
    
        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Item added successfully');
    }

    public function changeQuantity(Request $request)
    {
        // Validate the request data
        $request->validate([
            'temp_cash_id' => 'required|exists:temp_cashes,id',
            'quantity' => 'required|numeric|min:1',
        ]);
    
        // Retrieve the temp cash item
        $tempCash = TempCash::findOrFail($request->temp_cash_id);
    
        // Retrieve the associated purchase
        $purchase = Purchase::find($tempCash->purchase_id);
    
        // Check if the requested quantity exceeds the item stock
        if ($request->quantity > $purchase->item_stock) {
            return redirect()->back()->with('error', 'Requested quantity exceeds available stock');
        }
    
        // Update the quantity for the given temp_cash_id
        $tempCash->quantity = $request->quantity;
        $tempCash->save();
    
        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Quantity updated successfully');
    } 
    
    public function checkout(Request $request)
    {
        // Retrieve the total sale from the request
        $totalSale = $request->input('total_sale');
        
        // Retrieve the amount paid by the buyer
        $paidAmount = $request->input('change');
    
        // Calculate the change
        $change = $paidAmount - $totalSale;
    
        // Check if the change is negative or zero
        if ($change < 0) {
            return redirect()->back()->with('error', 'Amount paid is less than total sale');
        }
    
        // Retrieve all items from the TempCash table
        $tempCashes = TempCash::where('user_id', auth()->id())->get();
    
        // Loop through each item and copy it to the Cashier table
        foreach ($tempCashes as $tempCash) {
            Cashier::create([
                'user_id' => $tempCash->user_id,
                'purchase_id' => $tempCash->purchase_id,
                'item_name' => $tempCash->item_name,
                'item_image' => $tempCash->item_image,
                'item_price' => $tempCash->item_price,
                'quantity' => $tempCash->quantity,
            ]);
    
            // Update the item_stock in the Purchase table
            $purchase = Purchase::findOrFail($tempCash->purchase_id);
            $purchase->item_stock -= $tempCash->quantity;
            $purchase->save();
        }
    
        // Delete all items from the TempCash table
        TempCash::where('user_id', auth()->id())->delete();
    
        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Items checked out successfully. Change: ₱' . number_format($change, 2));
    }    

    public function remove(Request $request)
    {
        // Validate the request data
        $request->validate([
            'temp_cash_id' => 'required|exists:temp_cashes,id',
        ]);

        // Find the temp_cash record
        $tempCash = TempCash::findOrFail($request->temp_cash_id);

        // Delete the temp_cash record
        $tempCash->delete();

        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Item removed successfully');
    }
    
}
