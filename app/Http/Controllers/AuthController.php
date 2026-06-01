<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function signin()
    {
        return view('auth.signin');
    }

    public function signup()
    {
        return view('auth.signup');
    }

    public function store(Request $request)
    {
        // dd($request->all());
       
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:0|max:50',
                'email' => 'required|email|unique:users,email',
                'role'=> 'required|string',
                'password' => 'required|string|min:8|max:12',
               
            ]);
            
            $validated['password'] = Hash::make($validated['password']);
             
            $user = User::create($validated);   

            if (!$user) {
                return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
            }
            Auth::login($user);
            
            return redirect()->route('admin.dashboard');

        } catch (ValidationException $e) {
            return back()->withErrors(['email' => 'email registered'])->withInput();

        }

    }

    public function verifyLoginCredentials(Request $request)
    {   

        $request->validate([
            'email' => 'required|string|exists:users,email',
            'password' => 'required|string|min:8|max:12',
            'remember_me' => 'nullable|boolean'
        ]);

        $cred = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $remember_me = $request->remember_me ? true : false;

        if (Auth::attempt($cred, $remember_me)) {
            $request->session()->regenerate();

           
            return redirect()->route('admin.dashboard');
            
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.'
        ])->withInput();

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->regenerate();

        return redirect()->route('home');
    }

}
