<?php
namespace App\Http\Controllers\Mail;
use App\Http\Controllers\Controller;
use App\Models\Mail\mail_emails;
use App\Classes\Mail\MailClass;
use DB;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
	public function __construct()
	{
    	//$this->middleware('auth');
	}
	
	public function webhook(Request $request)
	{
			$check = DB::table('mail_settings')->where('name','mail_email')->where('value',$request->input('recipient'))->first();
			
			$idUser = "";
			
			if(!$check)
			{
				//exit();
			}
			else
			{
				$idUser = $check->idUser;
			}
			
			$idUser = 1;
			$MailHeader = MailClass::MailHeader($request->input('message-headers'));
			if (array_key_exists('X-Mailgun-Sflag', $MailHeader))
			{
				$XMailgunSflag = $MailHeader['X-Mailgun-Sflag'];
			}
			else
			{
				$XMailgunSflag = "No";
			}
			
			if($XMailgunSflag=="Yes")
			{
				$type = 3;
			}
			else
			{
				$type = 1;	
			}
			
			
			
		    $mail_emails = new mail_emails;
			$mail_emails->recipient = $request->input('recipient');
			$mail_emails->sender = $request->input('sender');
			$mail_emails->from = $request->input('from');
			$mail_emails->subject = $request->input('subject');
			$mail_emails->body_plain = $request->input('body-plain');
			$mail_emails->stripped_text = $request->input('stripped-text');
			$mail_emails->stripped_signature = $request->input('stripped-signature');
			$mail_emails->body_html = $request->input('body-html');
			$mail_emails->stripped_html = $request->input('stripped-html');
			$mail_emails->attachment_count = $request->input('attachment-count');
			$mail_emails->attachment_x = $request->input('attachment-x');
			$mail_emails->timestamp = $request->input('timestamp');
			$mail_emails->signature = $request->input('signature');
			$mail_emails->message_headers = $request->input('message-headers');
			$mail_emails->content_id_map = $request->input('content-id-map');
			$mail_emails->type = $type;
			$mail_emails->idUser = $idUser;
			$mail_emails->save();
			
			if(!empty($_FILES))
			{
				foreach($_FILES as $file)
				{
					\Cloudinary::config(array( 
  						"cloud_name" => env('CLOUDINARY_NAME'), 
  						"api_key" => env('CLOUDINARY_KEY'), 
  						"api_secret" => env('CLOUDINARY_SECRET') 
					));
											   
					$cloudinary = \Cloudinary\Uploader::upload($file['tmp_name'],array("resource_type" => "raw"));
					DB::table('mail_attachments')
					->insert([
					'email_id'=>$mail_emails->id,
					'public_id'=> $cloudinary['public_id'],
					'version'=> $cloudinary['version'],
					'signature'=> $cloudinary['signature'],
					'resource_type'=> $cloudinary['resource_type'],
					'bytes'=> $cloudinary['bytes'],
					'type'=> $cloudinary['type'],
					'etag'=> $cloudinary['etag'],
					'url'=> $cloudinary['url'],
					'secure_url'=> $cloudinary['secure_url'],
					'original_filename'=> $file['name'],
					'idUser'=> $idUser]
					);
					
					
				}
			}
			
			
			$pushover_user = MailClass::getConf('pushover_user',$idUser);
			$pushover_app = MailClass::getConf('pushover_app',$idUser);
			
			
			
			if($pushover_app!="" && $pushover_user!="" && $type==1)
			{
		
				$url_link = url('') ."/mail/inbox/detail/". $mail_emails->id;
				curl_setopt_array($ch = curl_init(), array(
  				CURLOPT_URL => "https://api.pushover.net/1/messages.json",
  				CURLOPT_POSTFIELDS => array(
    			"token" => $pushover_app,
    			"user" => $pushover_user,
				"title" => $request->input('from'),
    			"message" => $request->input('subject'),
				"url" => $url_link,
				"url_title" => "Reply to ". $request->input('from'),
  				),
  				CURLOPT_SAFE_UPLOAD => true,
				));
				curl_exec($ch);
				curl_close($ch);
		
			}
			
	}
}
?>