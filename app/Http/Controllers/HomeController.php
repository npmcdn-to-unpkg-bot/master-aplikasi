<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Auth;
use App\Classes\Message\MessageClass;
use Config;
use DB;


class HomeController extends Controller
{
	public function __construct()
	{
    	$this->middleware('auth');
		$this->middleware('check.level:100');
	}
	
	protected function home()
	{
		$user = Auth::user();
		return view('home')->with('user',$user);
	}
}
?>