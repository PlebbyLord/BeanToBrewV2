<?php

namespace App\Http\Controllers;

use App\Models\Mapping;
use App\Models\Purchase;
use App\Models\Rating;
use App\Models\User;
use App\Models\ViewItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve users with role = 0
        $users = User::where('role', 0)->get();
    
        // Retrieve all branches (assuming Mapping is the model for branches)
        $branches = Mapping::all();
    
        // Pass filtered users data and branches data to the view
        return view('Features.users', compact('users', 'branches'));
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
    public function show(ViewItem $viewItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ViewItem $viewItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ViewItem $viewItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ViewItem $viewItem)
    {
        //
    }  
    
    public function storeAdmin(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address' => ['required', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'size:11'],
            'branch' => ['required', 'string', 'max:255'],
        ]);

        // Create the new user record
        User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'address' => $validatedData['address'],
            'mobile_number' => $validatedData['mobile_number'],
            'branch' => $validatedData['branch'],
            'role' => 1, // Set the role to admin
            'verification_status' => 1,
        ]);

        // Redirect back to the previous page with a success message
        return redirect()->back()->with('success', 'Admin user created successfully!');
    }

    public function admins(Request $request)
    {
        // Retrieve users with roles 1 and 2
        $users = User::whereIn('role', [1, 2])->get();
    
        // Retrieve all branches (assuming Mapping is the model for branches)
        $branches = Mapping::all();
    
        // Pass filtered users data and branches data to the view
        return view('features.admins', compact('users', 'branches'));
    }
}
