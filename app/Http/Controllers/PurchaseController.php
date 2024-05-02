<?php

namespace App\Http\Controllers;

use App\Models\Mapping;
use App\Models\Purchase;
use App\Models\TempInv;
use Carbon\Carbon;
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
    
            // Query the purchases table based on the selected coffee types and transfer_status
            $userItems = Purchase::whereIn('coffee_type', $selectedCoffeeTypes)
                ->where('transfer_status', 0) // Add condition for transfer_status
                ->distinct()
                ->get();
        } else {
            // No filter parameters provided, so get all items with transfer_status = 0
            $userItems = Purchase::where('transfer_status', 0) // Add condition for transfer_status
                ->distinct()
                ->get();
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
            'item_description' => 'required|string',
            'coffee_type' => 'required|in:green,roasted,grinded',
            'expiry_date' => 'required|date|after_or_equal:today',
            'production_date' => 'required|date|after_or_equal:today',
            'branch' => 'required|exists:mappings,name',
        ]);
    
        $user = auth()->user();
    
        // Fetch the production date from the request
        $productionDate = $request->production_date;
    
        // Calculate expiry date based on coffee type
        $expiryDate = $productionDate; // Set expiry date as production date initially
    
        switch ($request->coffee_type) {
            case 'green':
                $expiryDate = Carbon::parse($productionDate)->addYears(2);
                break;
            case 'roasted':
                $expiryDate = Carbon::parse($productionDate)->addYears(1)->addMonths(6);
                break;
            case 'grinded':
                $expiryDate = Carbon::parse($productionDate)->addMonths(6);
                break;
        }
    
        // Create a new Purchase instance
        $item = new Purchase();
        $item->user_id = $user->id;
        $item->item_name = $request->item_name;
        $item->coffee_type = $request->coffee_type;
        $item->production_date = $productionDate;
        $item->expiry_date = $expiryDate;
        $item->branch = $request->branch;
    
        // Handle item_image upload and save its path
        if ($request->hasFile('item_image')) {
            $imageFile = $request->file('item_image');
            $imagePath = $imageFile->move(public_path('storage/images'), $imageFile->getClientOriginalName());
            $item->item_image = 'images/' . $imageFile->getClientOriginalName(); // Save image path in the 'item_image' attribute
        }
    
        $item->item_price = $request->item_price;
        $item->item_stock = $request->item_stock;
        $item->item_description = $request->item_description;
        $item->save();
    
        return redirect()->back()->with('success', 'Item Created');
    }
    
    public function transferPage(Request $request)
    {
        $currentUserBranch = auth()->user()->branch;

        // Check if filter parameters are provided in the request
        if ($request->filled('coffee_type')) {
            // Validate the request
            $request->validate([
                'coffee_type' => 'required|array', // Ensure coffee_type is an array
                'coffee_type.*' => 'in:green,roasted,grinded', // Validate each coffee type
            ]);
    
            // Extract the selected coffee types from the request
            $selectedCoffeeTypes = $request->input('coffee_type');
    
            // Query the purchases table for items from other branches based on selected coffee types
            $otherBranchItems = Purchase::whereIn('coffee_type', $selectedCoffeeTypes)
                ->where('branch', '!=', $currentUserBranch)
                ->get();
        } else {
            // No filter parameters provided, so get all items from other branches
            $otherBranchItems = Purchase::where('branch', '!=', $currentUserBranch)->get();
        }
    
        // Retrieve items from the temp_invs table
        $tempInvs = TempInv::where('user_id', auth()->id())->get();
    
        // Pass the filtered or unfiltered other branch items and temp inv items to the view
        return view('Features.transfer', ['otherBranchItems' => $otherBranchItems, 'tempInvs' => $tempInvs]);
    }

    public function addToTempInv(Request $request)
    {
        // Validate the request data
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
        ]);
        
        // Retrieve the purchase details
        $purchase = Purchase::findOrFail($request->purchase_id);
    
        // Retrieve the current user's branch
        $currentUserBranch = auth()->user()->branch;
    
        // Calculate transfer and arrival dates
        $transferDate = Carbon::today();
        $arrivalDate = Carbon::today()->addDays(2);
    
        // Check if the item already exists in the temp_invs table
        $existingTempInv = TempInv::where('purchase_id', $request->purchase_id)
            ->where('user_id', auth()->id())
            ->first();
    
        if ($existingTempInv) {
            // Increment the quantity by 1
            $existingTempInv->increment('quantity', 1);
        } else {
            // Create a new record in the temp_invs table
            TempInv::create([
                'user_id' => auth()->id(),
                'purchase_id' => $request->purchase_id,
                'item_name' => $purchase->item_name,
                'item_image' => asset('storage/' . $purchase->item_image), // Use placeholder image if item image is not available
                'item_price' => $purchase->item_price,
                'item_stock' => $purchase->item_stock,
                'expiry_date' => $purchase->expiry_date,
                'item_description' => $purchase->item_description,
                'coffee_type' => $purchase->coffee_type,
                'branch' => $purchase->branch,
                'production_date' => $purchase->production_date,
                'transfer_date' => $transferDate,
                'requested_by' => $currentUserBranch,
                'transfer_status' => 1,
                'quantity' => 1,
            ]);
        }
    
        return redirect()->back()->with('success', 'Item added successfully');
    }
    
    public function changeQuantity(Request $request)
    {
        // Validate the request data
        $request->validate([
            'temp_inv_id' => 'required|exists:temp_invs,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        // Retrieve the temp inventory item
        $tempInv = TempInv::findOrFail($request->temp_inv_id);

        // Retrieve the associated purchase
        $purchase = Purchase::find($tempInv->purchase_id);

        // Check if the requested quantity exceeds the item stock
        if ($request->quantity > $purchase->item_stock) {
            return redirect()->back()->with('error', 'Requested quantity exceeds available stock');
        }

        // Update the quantity for the given temp_inv_id
        $tempInv->quantity = $request->quantity;
        $tempInv->save();

        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Quantity updated successfully');
    }

    public function remove(Request $request)
    {
        // Validate the request data
        $request->validate([
            'temp_inv_id' => 'required|exists:temp_invs,id',
        ]);

        // Find the temp_inv record
        $tempInv = TempInv::findOrFail($request->temp_inv_id);

        // Delete the temp_inv record
        $tempInv->delete();

        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Item removed successfully');
    }

    public function request()
    {
        // Retrieve all items from the TempInv table
        $tempInvs = TempInv::where('user_id', auth()->id())->get();
    
        // Retrieve the current user's branch
        $currentUserBranch = auth()->user()->branch;
    
        // Calculate transfer and arrival dates
        $transferDate = Carbon::today();
    
        // Loop through each item and copy it to the Purchase table
        foreach ($tempInvs as $tempInv) {
            // Retrieve the associated Purchase model
            $purchase = Purchase::findOrFail($tempInv->purchase_id);
    
            // Update the item_stock in the Purchase table
            $purchase->item_stock -= $tempInv->quantity; // Subtract quantity from item_stock
            $purchase->save();
    
            // Create a new Purchase record
            Purchase::create([
                'user_id' => $tempInv->user_id,
                'item_name' => $tempInv->item_name,
                'item_image' => $purchase->item_image, // Retrieve item_image from the associated Purchase model
                'item_price' => $tempInv->item_price, // Use item_price from TempInv
                'item_stock' => $tempInv->quantity, // Set item_stock as the quantity
                'expiry_date' => $tempInv->expiry_date,
                'item_description' => $tempInv->item_description,
                'coffee_type' => $tempInv->coffee_type,
                'branch' => $tempInv->branch,
                'production_date' => $tempInv->production_date,
                'transfer_date' => $transferDate,
                'requested_by' => $currentUserBranch,
                'transfer_status' => 1,
                // Add other fields as needed
            ]);
    
            // Delete the item from TempInv
            $tempInv->delete();
        }
    
        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Request sent successfully');
    }    

    // Controller function for approving transfer status
    public function approveTransferStatus($purchaseId)
    {
        // Find the purchase record
        $purchase = Purchase::findOrFail($purchaseId);
    
        // Calculate the arrival date (2 days after the transfer date)
        $arrivalDate = Carbon::parse($purchase->transfer_date)->addDays(2);
    
        // Update the purchase record
        $purchase->arrival_date = $arrivalDate;
        $purchase->transfer_status = 2; // Set transfer_status to 2 (approved)
        $purchase->branch = $purchase->requested_by; // Set branch to requested_by
    
        // Save the changes
        $purchase->save();
    
        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Transfer status approved successfully');
    }

    // Controller function for rejecting transfer status
    public function rejectTransferStatus($purchaseId)
    {
        // Find the purchase record
        $purchase = Purchase::findOrFail($purchaseId);
    
        // Retrieve the item stock
        $itemStock = $purchase->item_stock;
    
        // Find another item with the same attributes to transfer the stock to
        $replacementItem = Purchase::where('item_name', $purchase->item_name)
            ->where('item_price', $purchase->item_price)
            ->where('item_image', $purchase->item_image)
            ->where('branch', $purchase->branch)
            ->where('item_description', $purchase->item_description)
            ->where('coffee_type', $purchase->coffee_type)
            ->where('production_date', $purchase->production_date)
            ->where('expiry_date', $purchase->expiry_date)
            ->where('transfer_status', 0) // Only consider items with transfer_status = 0
            ->first();
    
        // If no replacement item is found, you may handle this case accordingly
    
        // Add the rejected item's stock to the replacement item
        if ($replacementItem) {
            $replacementItem->item_stock += $itemStock;
            $replacementItem->save();
        }
    
        // Delete the rejected item
        $purchase->delete();
    
        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Transfer status rejected successfully');
    }

    public function markReceived($purchaseId)
    {
        // Find the purchase record
        $purchase = Purchase::findOrFail($purchaseId);

        // Update the transfer_status to 3 (received)
        $purchase->transfer_status = 0;
        $purchase->save();

        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Transfer status marked as received successfully');
    }
}
