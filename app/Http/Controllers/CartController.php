<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::with('purchase.user')->get();
        return view('Features.cart', compact('carts'));
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
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('features.cart')->with('success', 'Item deleted successfully.');
    }

    public function addToCart(Request $request)
    {
        // Validate the request data
        $request->validate([
            'purchase_id' => 'required',
            'quantity' => 'required|numeric|min:1',
        ]);

        // Find the selected item using the provided item_id
        $selectedItem = Purchase::find($request->input('purchase_id'));
        
        // Check if the item is already in the user's cart and belongs to the same company
        $existingCartItem = Cart::where('user_id', Auth::id())
            ->where('purchase_id', $selectedItem->id)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($existingCartItem) {
            // If the item is already in the cart
            if ($existingCartItem->checkout_status == 2) {
                // Create a new Cart instance
                $cart = new Cart([
                    'user_id' => Auth::id(),
                    'purchase_id' => $selectedItem->id,
                    'item_image' => $selectedItem->item_image,
                    'item_name' => $selectedItem->item_name,
                    'item_price' => $selectedItem->item_price,
                    'expiry_date' => $selectedItem->expiry_date,
                    'quantity' => $request->input('quantity'),
                ]);

                $cart->save();
            } elseif ($existingCartItem->checkout_status == 1) {
                // Update the quantity if checkout_status is 1
                $existingCartItem->quantity += $request->input('quantity');
                $existingCartItem->save();
            }
        } else {
            // If the item is not in the cart, create a new Cart instance
            $cart = new Cart([
                'user_id' => Auth::id(),
                'purchase_id' => $selectedItem->id,
                'item_image' => $selectedItem->item_image,
                'item_name' => $selectedItem->item_name,
                'item_price' => $selectedItem->item_price,
                'expiry_date' => $selectedItem->expiry_date,
                'quantity' => $request->input('quantity'),
            ]);

            $cart->save();
        }

        return redirect()->route('features.cart')->with('success', 'Item added to cart successfully.');
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1',
            'cartItemId' => 'required|exists:carts,id',
        ]);
    
        $user = auth()->user();
    
        // Find the existing cart item for the user
        $cartItem = Cart::find($request->input('cartItemId'));
    
        if (!$cartItem || $cartItem->user_id !== $user->id) {
            // Handle the case where the cart item for the user is not found or doesn't belong to the user
            return redirect()->back()->with('error', 'Invalid cart item.');
        }
    
        // Check if the new quantity exceeds item_stock
        if ($cartItem->purchase->item_stock < $request->input('quantity')) {
            return redirect()->back()->with('error', 'Quantity exceeds available stock.');
        }
    
        // Update the quantity
        $cartItem->quantity = $request->input('quantity');
        $cartItem->save();
    
        return redirect()->back()->with('success', 'Quantity updated successfully');
    }
}
