<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // return route('login');
            return $request->expectsJson()
            ? null
            : (app()->environment('local') ? 'http://localhost/sourcing_plan/public/login' : 'https://sourcing-plan.wsystem.online/login');
        }
        return null;
    }
}
