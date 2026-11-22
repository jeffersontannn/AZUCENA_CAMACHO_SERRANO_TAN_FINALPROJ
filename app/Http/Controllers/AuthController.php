<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function create()
    {
        if (session()->has('user_id')) {
            return redirect('/dashboard');
        }

        return view('login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session()->put([
                'user_id' => $user->id,
                'user_name' => $user->full_name,
                'username' => $user->username,
                'avatar' => $user->avatar,
            ]);

            return redirect('/dashboard');
        }

        return redirect()->back()
            ->with('error', 'Invalid username or password.')
            ->withInput();
    }

    public function destroy()
    {
        session()->forget(['user_id', 'user_name', 'username', 'avatar']);

        return redirect('/login')->with('success', 'Logged out successfully.');
    }
}
