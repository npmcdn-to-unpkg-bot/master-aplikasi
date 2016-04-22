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
		
	public function getCompose()
	{
		$user = Auth::user();
		$key = md5(date('YmdHis'));
		return view('mail.compose')->with('user',$user)->with('key',$key);
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
		
		
		Mail::queue('mail.email-format',['konten' => $konten], function ($m) use ($subject,$to) {
            $m->from('aku@budi.my.id', 'Budi');
			$m->to($to)->subject($subject);
        });
		
		return redirect('mail/inbox')->with('user',$user);
	}
	
	public function getData()
	{
		$user = Auth::user();
		$posts = DB::table('mail_emails')->select(['id', 'sender', 'from', 'timestamp','attachment_count', 'subject' ])->where('idUser',$user->id);
		
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