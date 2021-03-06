<?php

namespace App\Http\Controllers\CustomAuth;
use App\Http\Controllers\Controller;
use Auth;
use Config;
use DB;


class DashboardController extends Controller
{
	public function __construct()
	{
    	$this->middleware('auth');
	}
	
	protected function dashboard()
	{
		$user = Auth::user();
		return view('customauth.dashboard')->with('user',$user);
	}
	
	
}
?>