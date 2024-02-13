<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Rating;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Rating $rating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        //
    }

    public function save(Request $request)
    {
        // Validate the request data
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);
        
        // Retrieve the cart
        $cart = Cart::findOrFail($request->input('cart_id'));
        
        // Create a new rating instance
        $rating = new Rating();
        $rating->purchase_id = $cart->purchase->id; // Assuming purchase_id is the foreign key
        $rating->item_name = $cart->item_name;
        $rating->user_id = Auth::id();
        $rating->rating = $request->input('rating');
        $rating->comment = $request->input('comment');
        
        // Save the rating
        $rating->save();
        
        // Change rating_status in the carts table
        $cart->rate_status = 2;
        $cart->save();
        
        // Redirect back with a success message
        return redirect()->route('features.orders')->with('success', 'Rating saved successfully.');
    }
    
}
