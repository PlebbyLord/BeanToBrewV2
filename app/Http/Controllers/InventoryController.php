<?php

namespace App\Http\Controllers;

use App\Models\Mapping;
use App\Models\Purchase;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
       /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

     public function index()
     {
         // Retrieve the current user
         $currentUser = auth()->user();
     
         // If the user has role 2 (admin), show all purchases
         if ($currentUser->role == 2) {
             $purchases = Purchase::all();
         } else {
             // If not admin, filter purchases based on the user's branch
             $purchases = Purchase::where('branch', $currentUser->branch)->get();
         }
         
         // Retrieve branches from the mappings table
         $branches = Mapping::pluck('name', 'id');
     
         return view('Features.inventory', compact('purchases', 'branches'));
     }
     
    
}
