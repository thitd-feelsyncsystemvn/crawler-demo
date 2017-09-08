<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyController extends Controller
{
    public function Hello()
    {
    	$laravel = app();
		$version = $laravel::VERSION;
    	echo $version;
    }
    public function GetURL(Request $request)
    {
    	return $request->url();
    }
    public function postForm(Request $request)
    {
    	echo $request->has('name'); 
    }


}
