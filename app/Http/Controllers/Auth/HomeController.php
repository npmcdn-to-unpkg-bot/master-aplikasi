<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Auth;
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
		
		return view('auth.home')->with('user',$user);
	}
}
?>