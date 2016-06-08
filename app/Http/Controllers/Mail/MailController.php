<?php
namespace App\Http\Controllers\Mail;
use App\Http\Controllers\Controller;
use App\Classes\Mail\MailClass;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use Validator;
use Redirect;
use Mail;
use App\Models\Mail\mail_emails;
use Html2Text;

class MailController extends Controller
{
	public function __construct()
	{
    	$this->middleware('auth');
	}
	
	public function postAttachDelete(Request $request)
	{
		$user = Auth::user();
		$filename = $request->input('name');
		DB::table('mail_tmp')->where('idUser',$user->id)->where('file',"../storage/logs/". $filename)->delete();
		if(file_exists("../storage/logs/". $filename))
		{
			unlink("../storage/logs/". $filename);
		}
	}
	
	public function postAttachAdd(Request $request)
	{
		$ret = array();
		$user = Auth::user();
		$output_dir = "../storage/logs/";
		$key = $request->input('key');
		if(!is_array($_FILES["myfile"]["name"])) //single file
		{
			$namaTemp = rand(5, 15);
			$namaTemp = $namaTemp ."_". date('YmdHis');
 	 		$fileName = $_FILES["myfile"]["name"];
			$array = explode(".",$fileName);
			$pathfile = $output_dir.$fileName;
 			move_uploaded_file($_FILES["myfile"]["tmp_name"],$pathfile);
    		$ret[]= $fileName;
		
			list($width, $height, $type, $attr) = getimagesize($pathfile);
    		$size = getimagesize($pathfile);
		
			$cek = DB::table('mail_tmp')->where('idUser',$user->id)->where('file',$pathfile)->count();
			if($cek==0)
			{
				DB::table('mail_tmp')->insert(['file'=>$pathfile,'idUser'=>$user->id,'key'=>$key]);
			}
			
		}
		else
		{
			$fileCount = count($_FILES["myfile"]["name"]);
	  		for($i=0; $i < $fileCount; $i++)
	  		{
				$namaTemp = rand(5, 15);
				$namaTemp = $namaTemp ."_". date('YmdHis');
				$fileName = $_FILES["myfile"]["name"][$i];
				$array = explode(".",$fileName);
				$pathfile = $output_dir.$fileName;
	  			$fileName = $_FILES["myfile"]["name"][$i];
				move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$pathfile);
	  			$ret[]= $fileName;
		
				list($width, $height, $type, $attr) = getimagesize($pathfile);
    			$size = getimagesize($pathfile);
		
				$cek = DB::table('mail_tmp')->where('idUser',$user->id)->where('file',$pathfile)->count();
				if($cek==0)
				{
					DB::table('mail_tmp')->insert(['file'=>$pathfile,'idUser'=>$user->id,'key'=>$key]);
				}
		
	  		}
		}
		echo json_encode($ret);
	}
		
	public function getCompose($id=0)
	{
		$user = Auth::user();
		$key = md5(date('YmdHis'));
		
		$accounts = DB::table('mail_accounts')->where('idUser',$user->id)->get();
		
		$email = \App\Models\Mail\mail_emails::where('idUser',$user->id)->where('id',$id)->first();
		if(count($email))
		{
			$from = $email->sender;
			$subject = "Re: ". $email->subject;
			$replay_message = "On ". $email->created_at .", ". $email->from ." wrote: <br />&gt; ". str_replace("<br />","<br />&gt;",nl2br($email->body_plain));
		}
		else
		{
			$from = "";
			$subject = "";
			$replay_message = "";
		}
		
		return view('mail.compose')->with('user',$user)->with('key',$key)->with('replay_message',$replay_message)->with('from',$from)->with('subject',$subject)->with('accounts',$accounts);
	}
	
	public function postCompose(Request $request)
	{
		$user = Auth::user();
		$rules = [
			'subject' => 'required',
            'to' => 'required|email',
        ];
		
		$validator = Validator::make($request->all(), $rules);
		
		if($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator);
        }
		
		$subject =  $request->input('subject');
		$to =  $request->input('to');
		$account =  $request->input('account');
		$konten =  $request->input('konten');
		$key = $request->input('key');
		$konten_plain = "";
		if($konten!="") $konten_plain = Html2Text\Html2Text::convert($konten);
		
		$mail = array();
		$from_email = DB::table('mail_accounts')->where('id',$account)->where('idUser', $user->id)->first();
		
		$mail['mail_name'] = $from_email->name;	
		$mail['mail_email'] = $from_email->email;	
		
		$result = DB::table('mail_tmp')->where('key',$key)->where('idUser',$user->id)->get();
		
		Mail::send(['mail.html-format','mail.text-format'],['konten' => $konten], function ($m) use ($subject,$to,$result,$key,$user,$mail) {
            $m->from($mail['mail_email'], $mail['mail_name']);
			$m->to($to)->subject($subject);
			foreach($result as $rs)
			{
				$m->attach($rs->file);
			}
        });
		
		$attachment_count = 0;
		foreach($result as $rs)
			{
				$attachment_count++;
				DB::table('mail_tmp')->where('key',$key)->where('file',$rs->file)->where('idUser',$user->id)->delete();
				unlink($rs->file);
			}
		
			$mail_emails = new mail_emails;
			$mail_emails->recipient = $to;
			$mail_emails->sender = $mail['mail_email'];
			$mail_emails->from = $mail['mail_name'].' <'.$mail['mail_email'].'>';
			$mail_emails->subject = $subject;
			$mail_emails->body_plain = $konten_plain;
			$mail_emails->stripped_text = $konten_plain;
			$mail_emails->stripped_signature = "";
			$mail_emails->body_html = $konten;
			$mail_emails->stripped_html = $konten;
			$mail_emails->attachment_count = $attachment_count;
			$mail_emails->timestamp = strtotime("now");
			$mail_emails->type = 2;
			$mail_emails->idUser = $user->id;
			$mail_emails->save();
		
		return redirect('mail/sent')->with('user',$user);
	}
	
	public function getData($type)
	{
		$user = Auth::user();
		
		switch($type)
		{
			case "trash":
				$type = "0";
			break;
			case "sent":
				$type = "2";
			break;
			case "spam":
				$type = "3";
			break;
			default:
				$type = "1";	
		}
		
		$posts = DB::table('mail_emails')->select(['id', 'sender', 'from', 'timestamp','attachment_count', 'type', 'subject' ])->where('idUser',$user->id)->where('type',$type)->orderBy('id','desc');
		
        return Datatables::of($posts)
		->filterColumn('sender', function($query, $keyword) {
                $query->where("sender", 'like',"%{$keyword}%")->orWhere("subject", 'like',"%{$keyword}%");
            })
		->addColumn('sender', function ($post) {
				$attachment_count = $post->attachment_count;
				if($attachment_count=="") $attachment_count = 0;
                return '<b>'. htmlentities($post->from) .'</b><br />'.tglIndo(strtotime(date('Y-m-d H:i:s', $post->timestamp)),"z",7).'<br />'. $post->subject .'<br /><small><i>Attachment : '. $attachment_count .'</i></small>';
            })
		->addColumn('action', function ($post) {
				switch($post->type)
				{
					case 0:
						$type = 'trash';
						$tambahan = '&nbsp;<button id="btn-del" type="button" onClick="move(\''. $post->id .'\')" class="btn btn-warning btn-sm"><b class="fa fa-mail-forward"> Move to inbox </b></button>';
						$command = "del";	
					break;
					case 2:
						$type = 'sent';
						$tambahan = "";
						$command = "hapus";
					break;
					case 3:
						$type = 'spam';
						$tambahan = '&nbsp;<button id="btn-del" type="button" onClick="move(\''. $post->id .'\')" class="btn btn-warning btn-sm"><b class="fa fa-mail-forward"> Move to inbox </b></button>';
						$command = "hapus";
					break;
					default:
						$type = 'inbox';
						$tambahan = "";
						$command = "hapus";
				}
				
				
				
				$column = '<button id="btn-del" type="button" onClick="window.location=\'/mail/compose/'.$post->id.'\'" class="btn btn-primary btn-sm"><b class="fa fa-edit"> Reply </b></button>&nbsp;<button id="btn-edit" onClick="window.location=\'/mail/'.$type.'/detail/'.$post->id.'\'" type="button" class="btn btn-success btn-sm"><b class="fa fa-pencil"> View </b></button>&nbsp;<button id="btn-del" type="button" onClick="'. $command .'(\''. $post->id .'\')" class="btn btn-danger btn-sm"><b class="fa fa-trash-o"> Delete </b></button>'. $tambahan;
                return $column;
            })
		->make(true);
	}
	
	public function getInboxDetail($type,$id)
	{
		$user = Auth::user();
		$result = \App\Models\Mail\mail_emails::with('attachments')
				   ->where('id',$id)
				   ->where('idUser',$user->id)
				   ->first();
		switch($result->type)
		{
			case 0:
				$type = "trash";
				$tambahan = '&nbsp;<button id="btn-del" type="button" onClick="move(\''. $result->id .'\')" class="btn btn-warning btn-sm"><b class="fa fa-mail-forward"> Move to inbox </b></button>';
				$command = "del";
			break;
			case 2:
				$type = "sent";
				$tambahan = "";
				$command = "hapus";
			break;
			case 3:
				$type = "spam";
				$tambahan = '&nbsp;<button id="btn-del" type="button" onClick="move(\''. $result->id .'\')" class="btn btn-warning btn-sm"><b class="fa fa-mail-forward"> Move to inbox </b></button>';
 				$command = "hapus";
			break;
			default:	
				$type = "inbox";
				$tambahan = "";
				$command = "hapus";
		}
		
		$button = '<button id="btn-del" type="button" onClick="window.location=\'/mail/compose/'.$result->id.'\'" class="btn btn-primary btn-sm"><b class="fa fa-edit"> Reply </b></button>&nbsp;<button id="btn-del" type="button" onClick="'. $command .'(\''.$result->id.'\')" class="btn btn-danger btn-sm"><b class="fa fa-trash-o"> Delete </b></button>'. $tambahan;
		
		return view('mail.mail-detail')->with('result',$result)->with('user',$user)->with('type',$type)->with('button',$button);
	}
	
	public function getIndex($type)
	{
		$user = Auth::user();
		switch($type)
		{
			case "sent":
				$type = "sent";
			break;
			case "trash":
				$type = "trash";
			break;
			case "spam":
				$type = "spam";
			break;
			default:
				$type = "inbox";	
		}
    	return view('mail.mail')->with('user',$user)->with('type',$type);
	}
	
	
	
	public function getDownload($id)
	{
		$user = Auth::user();
		$result = \App\Models\Mail\mail_attachments::where('idUser',$user->id)
				  ->where('id',$id)
				  ->first();
				  
		$nametemp = $result->public_id;
		$path = "../storage/logs/". $nametemp;
		file_put_contents($path, file_get_contents($result->secure_url));
		return response()->download($path, $result->original_filename)->deleteFileAfterSend($path);
	}
	
	
	
	public function getDeleteData($id)
	{
		$user = Auth::user();
		
		$result = DB::table('mail_attachments')->where('email_id',$id)->where('idUser',$user->id)->get();
		foreach($result as $rs)
		{
			
				\Cloudinary::config(array( 
  					"cloud_name" => env('CLOUDINARY_NAME'), 
  					"api_key" => env('CLOUDINARY_KEY'), 
  					"api_secret" => env('CLOUDINARY_SECRET') 
				));
				
				\Cloudinary\Uploader::destroy($rs->public_id,array("resource_type" => "raw"));
								
		}
		DB::table('mail_attachments')->where('email_id',$id)->where('idUser',$user->id)->delete();
		DB::table('mail_emails')->where('id',$id)->where('idUser',$user->id)->delete();
		
	}
	
	public function getEmptySpam()
	{
		$user = Auth::user();
		$results = \App\Models\Mail\mail_emails
				   ::where('idUser',$user->id)
				   ->where('type',3)
				   ->get();
		foreach($results as $result)
		{
			\App\Models\Mail\mail_emails::where('id',$result->id)->where('idUser',$user->id)->update(['type'=>0]);
		}
	}
	
	public function getEmptyTrash()
	{
		$user = Auth::user();
		$results = \App\Models\Mail\mail_emails::with('attachments')
				   ->where('idUser',$user->id)
				   ->where('type',0)
				   ->get();
		foreach($results as $result)
		{
	    	foreach($result->attachments as $attachment)
			{
				\Cloudinary::config(array( 
  					"cloud_name" => env('CLOUDINARY_NAME'), 
  					"api_key" => env('CLOUDINARY_KEY'), 
  					"api_secret" => env('CLOUDINARY_SECRET') 
				));
				
				\Cloudinary\Uploader::destroy($attachment->public_id,array("resource_type" => "raw"));
				DB::table('mail_attachments')->where('email_id',$attachment->id)->where('idUser',$user->id)->delete();
			}
			DB::table('mail_emails')->where('id',$result->id)->where('idUser',$user->id)->delete();
		}
	}
	
	public function getTrashData($id)
	{
		$user = Auth::user();
		DB::table('mail_emails')->where('id',$id)->where('idUser',$user->id)->update(['type'=>0]);
	}
	
	public function getMoveData($id)
	{
		$user = Auth::user();
		DB::table('mail_emails')->where('id',$id)->where('idUser',$user->id)->update(['type'=>1]);
	}
	
}
?>