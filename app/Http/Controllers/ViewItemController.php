<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\ViewItem;
use Illuminate\Http\Request;

class ViewItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $itemId = $request->query('id');    
        $selectedItem = Purchase::find($itemId);
    
        return view('Features.viewitem', compact('selectedItem'));
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

    public function showItem(Request $request, $id = null)
    {
        if (!$id) {
            $id = $request->query('id');
        }

        if (!$id) {
            return redirect()->back()->with('error', 'Item ID not provided.');
        }
    
        $selectedItem = Purchase::find($id);
    
        if (!$selectedItem) {
            return redirect()->back()->with('error', 'Item not found.');
        }
    
        return view('Features.viewitem', compact('selectedItem'));
    }
}
