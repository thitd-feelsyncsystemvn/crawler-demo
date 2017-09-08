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

Route::get('/', function () {
    return view('welcome');
});

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

Route::get('crawler', 'Crawler@index');
Route::post('crawler', ['as'=>'crawler', 'uses'=>'Crawler@crawler_start']);
