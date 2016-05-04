<?php
namespace App\Http\Controllers\Message;
use App\Http\Controllers\Controller;
use App\Classes\Message\MessageClass;
use DB;
use Auth;
use Illuminate\Http\Request;

class SettingController extends Controller
{
	public function __construct()
	{
		
    	$this->middleware('auth');
	}
	
	public function postSetting(Request $request)
	{
		$user = Auth::user();
		$setting = $request->input('setting');
		
		
		if($setting=="pushover_setting")
		{
			$pushover_user = $request->input('pushover_user');
			$pushover_app = $request->input('pushover_app');
			
			MessageClass::setConf('pushover_user',$pushover_user,$user->id);
			MessageClass::setConf('pushover_app',$pushover_app,$user->id);
			print('<div class="alert alert-success">
					<ul>
						Update Success          					
					</ul>
				</div>');
		}
		
	}
	
	public function getSetting()
	{
		$user = Auth::user();
		$pushover_user = MessageClass::getConf('pushover_user',$user->id);
		$pushover_app = MessageClass::getConf('pushover_app',$user->id);
		
		return view('message/setting')->with('user',$user)->with('pushover_user',$pushover_user)->with('pushover_app',$pushover_app);
	}
}
?>