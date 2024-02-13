<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userItems = Purchase::all();

        return view('Features.purchase', compact('userItems'));
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);

        $purchase->delete();

        return redirect()->back()->with('success', 'Purchase deleted successfully.');
    }

    public function saveItem(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:50',
            'item_image' => 'required|file|mimes:jpg,jpeg,png',
            'item_price' => 'required|numeric',
            'item_stock' => 'required|integer',
            'item_description' => 'required|string|max:255',
            'expiry_date' => 'required|date|after_or_equal:today',
        ]);
    
        $user = auth()->user();
    
        // Create a new Purchase instance
        $item = new Purchase();
        $item->user_id = $user->id;
        $item->item_name = $request->item_name;
    
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
}
