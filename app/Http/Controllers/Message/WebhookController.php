<?php
namespace App\Http\Controllers\Message;
use App\Http\Controllers\Controller;
use App\Classes\Message\MessageClass;
use DB;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
	public function __construct()
	{
    	//$this->middleware('auth');
	}
	
	public function webhook(Request $request,$service)
	{
		$from = "";
		$to = "";
		$idUser = "";
		
		if($service=="Nexmo")
		{
			$from = $request->input('msisdn');
			$to=$request->input('to');
			$text=$request->input('text');
			$messageId=$request->input('messageId');
		}
		if($service=="Twilio")
		{
			$from = $request->input('From');
			$to= $request->input('To');
			$text= $request->input('Body');
			$messageId= $request->input('MessageSid');
		}
		if($service=="Telerivet")
		{
			$from = $request->input('from_number');
			$to=$request->input('to_number');
			$text=$request->input('content');
			$messageId=$request->input('id');
		}
		
		if($from != "" && $to != "")
		{
			
			$from = MessageClass::FormatNumber($from);
			$to = MessageClass::FormatNumber($to);
			$idUser = MessageClass::getIdUserByPhone($to);
		}
		
		if($idUser!="")
		{
			//================================================================
			
			$kontak = MessageClass::NameContact($from,$idUser);
			$value = 
    	    array(
			'address' => $from,
			'date' => date('Y-m-d H:i:s'),
			'body' => $text,
			'type' => 1,
			'read' => 0,
			'phone' => $to,
			'archived'=>0,
			'idUser' => $idUser
			);
			$messageId = DB::table('msg_messages')->insertGetId($value);
			
			//================================================================
			
			$pushover_user = MessageClass::getConf('pushover_user',$idUser);
			$pushover_app = MessageClass::getConf('pushover_app',$idUser);
			if($pushover_app!="" && $pushover_user!="")
			{
				$url_link = url('') ."/message/inbox/detail/". $messageId;
				curl_setopt_array($ch = curl_init(), array(
  				CURLOPT_URL => "https://api.pushover.net/1/messages.json",
  				CURLOPT_POSTFIELDS => array(
    			"token" => $pushover_app,
    			"user" => $pushover_user,
				"title" => $kontak,
    			"message" => $text,
				"url" => $url_link,
				"url_title" => "Reply to ". $kontak,
  				),
  				CURLOPT_SAFE_UPLOAD => true,
				));
				curl_exec($ch);
				curl_close($ch);
			}
			
			//================================================================
			
		}
	}
}
?>