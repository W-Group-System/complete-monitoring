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

        if ($user->can('access quality')) {
            return redirect('/quality');
        } if ($user->can('access ccc quality')) {
            return redirect('/cccQuality');
        } elseif ($user->can('access quality approval')) {
            return redirect('/quality_approval');
        } elseif ($user->can('access ccc quality approval')) {
            return redirect('/ccc_quality_approval');
        }


        session(['api_token' => $token]);
        return redirect('/home');
        
    }


}
