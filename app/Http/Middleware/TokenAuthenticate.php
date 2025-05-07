<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TokenAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->has('token')) {
                $user = User::on('system1_db')->where('api_token', $request->token)->first();
                
                if ($user) {
                    Auth::guard()->loginUsingId($user->id, true); 
                    
                    return $next($request);
                }
            }
            
            if (app()->environment('local')) {
                return redirect('http://localhost/sourcing_plan/public/login')->with('error', 'Unauthorized access');
            } else {
                return redirect(url('/login'))->with('error', 'Unauthorized access');
            }
        }

        return $next($request);
    }
}
