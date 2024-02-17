<?php

namespace App\Http\Controllers;

use App\Models\Mapping;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
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
            $userItems = Purchase::whereIn('coffee_type', $selectedCoffeeTypes)->distinct()->get();
        } else {
            // No filter parameters provided, so get all items
            $userItems = Purchase::distinct()->get();
        }
    
        // Return the view with the filtered or unfiltered items
        return view('Features.purchase', compact('userItems'));
    }
    
    public function saveItem(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:50',
            'item_image' => 'required|file|mimes:jpg,jpeg,png',
            'item_price' => 'required|numeric',
            'item_stock' => 'required|integer',
            'item_description' => 'required|string|max:255',
            'coffee_type' => 'required|in:green,roasted,grinded',
            'expiry_date' => 'required|date|after_or_equal:today',
            'branch' => 'required|exists:mappings,name', // Validate that the selected branch exists
        ]);

        $user = auth()->user();

        // Create a new Purchase instance
        $item = new Purchase();
        $item->user_id = $user->id;
        $item->item_name = $request->item_name;
        $item->coffee_type = $request->coffee_type;
        
        // Set the branch ID
        $item->branch = $request->branch;

        // Handle item_image upload and save its path
        if ($request->hasFile('item_image')) {
            $imagePath = $request->file('item_image')->store('images', 'public');
            $item->item_image = $imagePath;
        }
        $item->item_price = $request->item_price;
        $item->item_stock = $request->item_stock;
        $item->item_description = $request->item_description;
        $item->expiry_date = $request->expiry_date;
        $item->save();

        return redirect()->back()->with('success', 'Item Created');
    }

    public function transferPage($purchase_id)
    {
        // Find the selected purchase item
        $selectedPurchase = Purchase::findOrFail($purchase_id);
    
        // Fetch all branches from the mappings table
        $branches = Mapping::pluck('name');
    
        return view('features.transfer', compact('selectedPurchase', 'branches'));
    }    
    
    public function transferItem(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'branch' => 'required|exists:mappings,name',
            'item_stock' => 'required|integer|min:1', // Ensure item_stock is a positive integer
        ]);
    
        // Find the purchase item by its ID
        $purchase = Purchase::findOrFail($request->purchase_id);
    
        // Get the selected branch name from the request
        $newBranch = $request->branch;
    
        // Get the selected item stock from the request
        $itemStock = $request->item_stock;
    
        // Find if the item already exists in the selected branch
        $existingPurchase = Purchase::where('item_name', $purchase->item_name)
                                     ->where('branch', $newBranch)
                                     ->first();
    
        if ($existingPurchase) {
            // Increment the item stock of the existing purchase
            $existingPurchase->item_stock += $itemStock;
            $existingPurchase->save();
        } else {
            // Create a new purchase record for the new branch
            $newPurchase = new Purchase();
            $newPurchase->user_id = $purchase->user_id; // Assuming you have a user_id field in the Purchase model
            $newPurchase->item_name = $purchase->item_name;
            $newPurchase->coffee_type = $purchase->coffee_type;
            $newPurchase->branch = $newBranch;
            $newPurchase->item_image = $purchase->item_image;
            $newPurchase->item_price = $purchase->item_price;
            $newPurchase->item_stock = $itemStock;
            $newPurchase->item_description = $purchase->item_description;
            $newPurchase->expiry_date = $purchase->expiry_date;
            $newPurchase->save();
        }
    
        // Decrement the item stock of the current purchase
        $purchase->item_stock -= $itemStock;
        $purchase->save();
    
        return redirect()->back()->with('success', 'Item transferred successfully.');
    }
    
}
