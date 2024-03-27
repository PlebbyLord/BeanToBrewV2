<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Features.profile');
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
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        //
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
    
        // Validate the request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // 10MB limit for image
        ]);
    
        // Update user details in the users table
        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'mobile_number' => $request->input('mobile_number'),
            'address' => $request->input('address'),
        ]);
    
        // Get the existing profile or create a new one
        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id], // Using the user_id to identify the profile
            []
        );
    
        // Handle profile picture upload if a new image is provided
        if ($request->hasFile('image1')) {
            $imagePath = $request->file('image1')->store('profile_pictures', 'public');
            $profile->profile_picture = $imagePath; // Update profile picture in profiles table
            $profile->save(); // Save the profile after updating profile picture
        }
    
        // Check if anything has changed in the users table
        if ($user->wasChanged('first_name') ||
            $user->wasChanged('last_name') ||
            $user->wasChanged('mobile_number') ||
            $user->wasChanged('address') ||
            $request->hasFile('image1')) {
            // Redirect to the profile page with a success message
            return redirect()->route('profile')->with('success', 'Profile updated successfully');
        } else {
            // Redirect to the profile page with an info message
            return redirect()->route('profile')->with('info', 'No update occurred');
        }
    }
    

}
