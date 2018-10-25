<?php

/**
 * Index
 */
Route::group(['middleware' => ['nocache']], function () {

	Route::view('/', 'index.browser')->middleware('auth');
	Route::view('/login', 'index.login')->name('login');

});

/**
 * Account
 */
Route::group(['prefix' => 'account', 'middleware' => ['nocache']], function () {

	Route::post('login', 'AccountController@login');
	Route::post('logout', 'AccountController@logout');

});


/**
 * Browser
 */
Route::group(['prefix' => 'browser', 'middleware' => ['auth']], function () {

	Route::get('/list', 'BrowserController@list');
	Route::get('/info', 'BrowserController@info');
	Route::get('/download', 'BrowserController@download');

	Route::post('/make-directory', 'BrowserController@makeDirectory');
	Route::post('/remove', 'BrowserController@remove');
	Route::post('/rename', 'BrowserController@rename');
	Route::post('/paste', 'BrowserController@paste');
	Route::post('/upload', 'BrowserController@upload');

});
