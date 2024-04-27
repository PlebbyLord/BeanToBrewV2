<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class VerificationController extends Controller
{
    public function showVerificationForm()
    {
        // Check if user is authenticated and has verification_status = 0
        if (auth()->user() && auth()->user()->verification_status === 0) {
            return view('verification.form');
        } else {
            return redirect('/');
        }
    }

    public function verify(Request $request)
    {
        $user = auth()->user();

        // Validate the verification code
        $validatedData = $request->validate([
            'verification_code' => 'required|digits:6',
        ]);

        // Check if the provided code matches the user's verification_code
        if ($user->verification_code === $validatedData['verification_code']) {
            // Update verification_status to 1
            $user->update(['verification_status' => 1]);

            return redirect('/')->with('success', 'Verification successful!');
        } else {
            return back()->with('error', 'Invalid verification code. Please try again.');
        }
    }
}
