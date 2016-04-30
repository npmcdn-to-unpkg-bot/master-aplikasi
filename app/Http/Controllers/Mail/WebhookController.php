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
			$mail_emails->type = 1;
			$mail_emails->idUser = 1;
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
					'idUser'=> 1]
					);
					
					
				}
			}
			
			$X_Mailgun_Sflag = MailClass::MailHeader($request->input('message-headers'),'X-Mailgun-Sflag');
			$pushover_user = MailClass::getConf('pushover_user',1);
			$pushover_app = MailClass::getConf('pushover_app',1);
			if($pushover_app!="" && $pushover_user!="" && $X_Mailgun_Sflag!="Yes")
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