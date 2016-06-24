<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Auth;
use Config;
use DB;


class AdminController extends Controller
{
	public function __construct()
	{
    	$this->middleware('auth');
		$this->middleware('check.level:100');
	}
	
	protected function admin()
	{
		$user = Auth::user();
		
		return view('auth.admin')->with('user',$user);
	}
	
	
}
?>