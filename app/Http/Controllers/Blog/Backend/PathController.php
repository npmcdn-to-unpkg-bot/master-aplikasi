<?php
namespace App\Http\Controllers\Blog\Backend;
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
		$path = DB::table('blog_access')->where('account','path')->where('idUser',$user->id)->first();
		$authorization = "Authorization: Bearer ". $path->access_token;
		
		// ====================================================================================
				$url = 'https://partner.path.com/1/user/self/friends';
				//if($konten=="") $konten = $judul.' - '.secure_url('');
				//$string_path = '{ "source_url": "'.$cloudinary['secure_url'].'", "caption": "'.$konten.'", "ic": true }';
				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL, $url);
				//curl_setopt($ch,CURLOPT_POST, count($string_path));
				//curl_setopt($ch,CURLOPT_POSTFIELDS, $string_path);
				curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
				curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				print_r($result);
				curl_close($ch);
			// ====================================================================================
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
						'client_id' => '69801cea67dc3cf2e208a84a01cba02684983060',
						'client_secret' => 'af9953efe2623115ee5d1d2a6719aebaba583d3e',
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
		
				
		exit($result);		
		
    	return Redirect('/blog/path');
	}
	
}
?>