<?php

namespace App\Http\Controllers;

use App\Models\Mapping;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MappingController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $mappings = Mapping::all();
        
        // Fetch items from the purchase table for each mapping
        $mappingItems = [];
        foreach ($mappings as $mapping) {
            $items = Purchase::where('branch', $mapping->name)->get();
            $mappingItems[$mapping->id] = $items;
        }
    
        $purchases = Purchase::all(); // Fetch all purchases
    
        return view('Features.mapping', [
            'mappings' => $mappings, 
            'mappingItems' => $mappingItems,
            'purchases' => $purchases, // Pass the $purchases variable to the view
        ]);
    }
    
    
    public function mappingsave()
    {
        return view('Features.mappingsave');
    }

    public function save(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
    
        // Create a new location
        $location = new Mapping();
        $location->name = $request->input('name');
        $location->latitude = $request->input('latitude');
        $location->longitude = $request->input('longitude');
    
        // Assign the user_id from the authenticated user
        $location->user_id = Auth::id(); 
    
        // Save the location to the database
        $location->save();
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Location saved successfully.');
    }

    public function getMappingData()
    {
        // Fetch all mappings from the database
        $mappings = Mapping::all();

        // Return the mappings as JSON response
        return response()->json($mappings);
    }
}
