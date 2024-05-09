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
    
        // Retrieve input values from the request
        $name = $request->input('name');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
    
        // Check if a location with the same name already exists
        $existingLocation = Mapping::where('name', $name)->first();
    
        if ($existingLocation) {
            // Location with the same name already exists, return error response
            return response()->json(['error' => 'A location with this name already exists.'], 422);
        }
    
        // Check if there is any existing location within 3 kilometers (3000 meters) of the new location
        $tooCloseLocation = Mapping::whereRaw('ST_Distance_Sphere(point(longitude, latitude), point(?, ?)) <= 2000', [$longitude, $latitude])
                                    ->exists();
    
        if ($tooCloseLocation) {
            // Location is too close to an existing location, return error response
            return response()->json(['error' => 'The location is too close to an existing location.'], 422);
        }
    
        // Create a new location
        $location = new Mapping();
        $location->name = $name;
        $location->latitude = $latitude;
        $location->longitude = $longitude;
        $location->user_id = Auth::id(); // Assign the user_id from the authenticated user 
    
        // Save the location to the database
        $location->save();
    
        // Return success response
        return response()->json(['success' => 'Location saved successfully.'], 200);
    }
    
    public function getMappingData()
    {
        // Fetch all mappings from the database
        $mappings = Mapping::all();

        // Return the mappings as JSON response
        return response()->json($mappings);
    }
}
