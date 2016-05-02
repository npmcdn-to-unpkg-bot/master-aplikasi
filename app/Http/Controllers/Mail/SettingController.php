<?php
namespace App\Http\Controllers\Mail;
use App\Http\Controllers\Controller;
use App\Classes\Mail\MailClass;
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
			
			MailClass::setConf('pushover_user',$pushover_user,$user->id);
			MailClass::setConf('pushover_app',$pushover_app,$user->id);
			print('<div class="alert alert-success">
					<ul>
						Update Success          					
					</ul>
				</div>');
		}
		
		if($setting=="mail_setting")
		{
			$mail_name = $request->input('mail_name');
			$mail_email = $request->input('mail_email');
			
			MailClass::setConf('mail_name',$mail_name,$user->id);
			MailClass::setConf('mail_email',$mail_email,$user->id);
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
		$pushover_user = MailClass::getConf('pushover_user',$user->id);
		$pushover_app = MailClass::getConf('pushover_app',$user->id);
		$mail_name = MailClass::getConf('mail_name',$user->id);
		$mail_email = MailClass::getConf('mail_email',$user->id);
		return view('mail/setting')->with('user',$user)->with('pushover_user',$pushover_user)->with('pushover_app',$pushover_app)->with('mail_name',$mail_name)->with('mail_email',$mail_email);
	}
}
?>