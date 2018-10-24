<?php

/**
 * Index
 */
Route::group(['middleware' => ['nocache']], function () {

	Route::view('/', 'index.browser');
	Route::view('/login', 'index.login');

});

/**
 * Account
 */
Route::group(['prefix' => 'account', 'middleware' => ['nocache']], function () {

	Route::post('login', 'AccountController@login');
	Route::post('logout', 'AccountController@logout');

});

