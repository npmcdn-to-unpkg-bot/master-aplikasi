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
		
		if($setting=="password_setting")
		{
			
			$strError = "";
			$current_password = $request->input('current_password');
			$new_password = $request->input('new_password');
			$confirm_new_password = $request->input('confirm_new_password');
			
			if($new_password!=$confirm_new_password)
			{
				$strError .= "<li>New password dan Confirm new password tidak sama</li>";
			}
			
			if($strError=="")
			{
				
				$credentials = ['email' => $user->email, 'password' => $current_password];
				if (Auth::validate($credentials)) {
    				
					$user->password = bcrypt($new_password);
					$user->save();
					
					print('<div class="alert alert-success">
					<ul>
						Update Success          					
					</ul>
					</div>');
					
				}
				else
				{
					
					$strError .= "<li>Password lama tidak cocok</li>";
				}
				
			}
			
			
			if($strError!="") print('<div class="alert alert-danger"><ul>'.$strError.'</ul></div>');
			
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