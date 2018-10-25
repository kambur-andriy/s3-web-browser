<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAuthenticated
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @param  string|null $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{

		if (!Auth::check()) {
			if ($request->ajax()) {
				return response()->json(
					[
						'message' => 'You have no access to this action.',
					],
					401
				);
			}

			return redirect()->route('login');
		}

		return $next($request);
	}
}
