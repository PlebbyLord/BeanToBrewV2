<?php

namespace App\Http\Controllers;

use App\Models\Mapping;
use Illuminate\Http\Request;

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
        return view('Features.mapping', ['mappings' => $mappings]);
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
