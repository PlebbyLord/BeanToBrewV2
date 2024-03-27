<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default, this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address' => ['nullable', 'string', 'max:255'], // Add validation for address field
            'mobile_number' => [
                'nullable',
                'string',
                'size:11',
                function ($attribute, $value, $fail) {
                    if (strlen($value) !== 11 || substr($value, 0, 2) !== '09') {
                        $fail('Please use a valid Philippine mobile number with a total length of 11 characters, starting with "09".');
                    }
                },
            ],
        ]);
    }
    

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Set default role to 0
        $role = 0;

        // Check if the email is beantobrew24@gmail.com
        if ($data['email'] === 'beantobrew24@gmail.com') {
            $role = 2; // Set role to 2
        }

        // Create user with role
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'address' => $data['address'], // Add address to user creation
            'mobile_number' => $data['mobile_number'], // Add mobile number to user creation
            'role' => $role, // Assign role based on email
        ]);
    }
}
