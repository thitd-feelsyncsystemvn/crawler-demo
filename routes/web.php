<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'Crawler@index');

Route::post('/', ['as'=>'crawler', 'uses'=>'Crawler@crawler_start']);

Route::get('/list_url/{para_rank}', 'Crawler@list_url');

Route::get('/page_detail/{para_page}', 'Crawler@list_anchor');

Route::get('test', function() {
	return "<h1>Hello World</h1>";
});
/*
Route::get('test/{params}', function($params) {
	echo "Parameters is ".$params;
});

Route::get('hello', 'MyController@Hello');

Route::get('MyRequest', 'MyController@GetURL');

Route::get('getForm', function() {
	return view('postForm');
});

Route::post('postForm',['as'=>'postForm', 'uses'=>'MyController@postForm']);
*/


