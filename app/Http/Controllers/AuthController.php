<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginWithToken(Request $request)
    {
        $token = $request->token;

        if (!$token) {
            return redirect('/login')->with('error', 'Token is required.');
        }

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return redirect('/login')->with('error', 'Invalid token.');
        }

        Auth::login($user);

         if (auth()->user()->position === 'Plant Analyst') {
            return redirect('/quality');
        }

        return redirect('/home');
        
    }


}
