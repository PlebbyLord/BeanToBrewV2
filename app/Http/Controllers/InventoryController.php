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
        $purchases = Purchase::all();
        
        // Retrieve branches from the mappings table
        $branches = Mapping::pluck('name', 'id');
    
        return view('Features.inventory', compact('purchases', 'branches'));
    }
    
}
