<?php

/**
 * Browser routes
 */

Route::get('/list', 'ApiController@list');
Route::get('/info', 'ApiController@info');
Route::get('/download', 'ApiController@download');

Route::post('/make-directory', 'ApiController@makeDirectory');
Route::post('/remove', 'ApiController@remove');
Route::post('/rename', 'ApiController@rename');
Route::post('/paste', 'ApiController@paste');
Route::post('/upload', 'ApiController@upload');

/**
 * Tags routes
 */
Route::post('/tags/categories', 'ApiController@createCategory');
Route::post('/tags', 'ApiController@createTag');
Route::post('/tags/categories/remove', 'ApiController@removeCategory');
Route::post('/tags/remove', 'ApiController@removeTag');


Route::get('/tags', 'ApiController@tags');
