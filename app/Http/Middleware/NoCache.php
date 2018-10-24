<?php

namespace App\Http\Middleware;

use Closure;

class NoCache
{
	/**
	 * Add CORS headers
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		return $next($request)
			->header('Cache-Control', 'no-store,no-cache,must-revalidate,post-check=0,pre-check=0');
	}
}
