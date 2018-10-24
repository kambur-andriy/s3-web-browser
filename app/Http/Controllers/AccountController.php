<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{

	/**
	 * Log in
	 *
	 * @param Request $request
	 *
	 * @return JSON
	 */
	public function logIn(Request $request)
	{
		$authCredentials = $request->only(['email', 'password']);

		$remember = $request->remember ?? true;

		$isAuthorized = Auth::attempt($authCredentials, $remember);

		if (!$isAuthorized) {
			return response()->json(
				[
					'message' => 'Login or password incorrect'
				],
				500
			);
		}

		return response()->json(
			[
				'homePage' => '/'
			]
		);

	}

	/**
	 * Log out
	 */
	public function logOut()
	{

		Auth::logout();

		return response()->json(
			[]
		);

	}

}
