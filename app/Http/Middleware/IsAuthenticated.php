<?php

namespace App\Http\Middleware;

use App\Facades\Account;
use App\Models\Account\AuthService;
use Closure;
use Illuminate\Support\Facades\App;

class IsAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
    	$authService = App::make(AuthService::class);


        if (!$authService->check()) {
            return redirect('/');
        }

        return $next($request);
    }
}
