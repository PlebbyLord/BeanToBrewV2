<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Rating;
use App\Models\ViewItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ViewItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $itemId = $request->query('id');    
        $selectedItem = Purchase::with('ratings')->find($itemId);
        
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
    
        // Load ratings associated with the selected item and paginate them by 3
        $comments = $selectedItem->ratings()->with('user')->orderBy('created_at', 'desc')->paginate(3);

        // Define an array of bad words
        $badWords = ['Shit', 'Fuck', 'Fucking', 'Ass', 'Asshole', 'bitch', 'garbage', 'putang ina', 'putangina', 'gago', 'gaga', 'amp', 'fuking', 'bullshit', 
        'bull shit', 'stupid', 'dumb', 'motherfucker', 'pota', 'mother fucker', 'fucker', 'puta', 'nigga', 'pussy', 'dick', 'tangina', 'bobo', 'tanga', 
        'ulol', 'gay', 'bading', 'bakla'];

        // Iterate through each comment and censor bad words
        foreach ($comments as $comment) {
            // Iterate through each bad word and replace them with asterisks
            $censoredComment = $comment->comment;
            foreach ($badWords as $badWord) {
                // Perform case-insensitive replacement using regular expression
                $censoredComment = preg_replace("/\b$badWord\b/i", str_repeat('*', strlen($badWord)), $censoredComment);
            }
            // Update the comment with the censored version
            $comment->censored_comment = $censoredComment;
        }        
    
        return view('Features.viewitem', compact('selectedItem', 'comments'));
    }
}
