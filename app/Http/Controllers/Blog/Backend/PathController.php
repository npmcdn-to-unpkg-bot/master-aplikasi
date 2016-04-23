<?php
namespace App\Http\Controllers\Blog;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use File;

class PathController extends Controller
{
	public function __construct()
	{
    	$this->middleware('auth');
	}
		
	public function getIndex(Request $request)
	{
		$user = Auth::user();
    	return view('blog.backend.path')->with('user',$user);
	}
	
	public function getAuth(Request $request)
	{
		$user = Auth::user();
		$cek = DB::table('blog_access')
			   ->where('account','path')
			   ->where('idUser',$user->id)
			   ->count();
		
		
				$fields_string="";
			    $url = 'https://partner.path.com/oauth2/access_token';
				$fields = array(
						'grant_type' => 'authorization_code',
						'client_id' => '8157a07833d7b090dfc0b21f29df9fc8623fd13f',
						'client_secret' => 'a46128c9183a1f9c601f8fa4e55829d1a05a7386',
						'code' => $request->input('code')
				);
				foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
				rtrim($fields_string, '&');
				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL, $url);
				curl_setopt($ch,CURLOPT_POST, count($fields));
				curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
				//curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				
				$test = explode('"',$result);
				
				curl_close($ch);
		
		
		if($cek==0)
		{
			DB::table('blog_access')->insert([
    			'account' => 'path', 'access_token' => $test[3], 'idUser' => $user->id
			]);
		}
		else
		{
			DB::table('blog_access')
            ->where('account', 'path')
			->where('idUser', $user->id)
            ->update(['access_token' => $test[3]]);
		}
		
				
				
		
    	return Redirect('/blog/path');
	}
	
}
?>