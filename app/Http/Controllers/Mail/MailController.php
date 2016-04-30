<?php
namespace App\Http\Controllers\Mail;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use Validator;
use Redirect;
use Mail;

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
		
	public function getCompose($id="")
	{
		$user = Auth::user();
		$key = md5(date('YmdHis'));
		
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
		
		return view('mail.compose')->with('user',$user)->with('key',$key)->with('replay_message',$replay_message)->with('from',$from)->with('subject',$subject);
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
		$konten =  $request->input('konten');
		$key = $request->input('key');
		
		$result = DB::table('mail_tmp')->where('key',$key)->where('idUser',$user->id)->get();
		
		Mail::send(['mail.html-format','mail.text-format'],['konten' => $konten], function ($m) use ($subject,$to,$result,$key,$user) {
            $m->from('aku@budi.my.id', 'Budi');
			$m->to($to)->subject($subject);
			foreach($result as $rs)
			{
				$m->attach($rs->file);
			}
        });
		
		foreach($result as $rs)
			{
				DB::table('mail_tmp')->where('key',$key)->where('file',$rs->file)->where('idUser',$user->id)->delete();
				unlink($rs->file);
			}
		
		return redirect('mail/inbox')->with('user',$user);
	}
	
	public function getData()
	{
		$user = Auth::user();
		$posts = DB::table('mail_emails')->select(['id', 'sender', 'from', 'timestamp','attachment_count', 'subject' ])->where('idUser',$user->id)->where('type',1);
		
        return Datatables::of($posts)
		->addColumn('tanggal', function ($post) {
                return date('Y-m-d H:i:s', $post->timestamp);
            })
		->addColumn('from_sender', function ($post) {
                return htmlentities($post->from);
            })
		->addColumn('action', function ($post) {
                return '<button id="btn-edit" onClick="window.location=\'/mail/inbox/detail/'.$post->id.'\'" type="button" class="btn btn-success btn-sm"><b class="fa fa-pencil"> View </b></button>&nbsp;<button id="btn-del" type="button" onClick="hapus(\''. $post->id .'\')" class="btn btn-danger btn-sm"><b class="fa fa-trash-o"> Delete </b></button>';
            })
		->make(true);
	}
	
	public function getIndex()
	{
		$user = Auth::user();
    	return view('mail.inbox')->with('user',$user);
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
	
	public function getInboxDetail($id)
	{
		$user = Auth::user();
		$result = \App\Models\Mail\mail_emails::with('attachments')
				   ->where('id',$id)
				   ->where('idUser',$user->id)
				   ->first();
		return view('mail.inbox-detail')->with('result',$result)->with('user',$user);
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
	
	
}
?>