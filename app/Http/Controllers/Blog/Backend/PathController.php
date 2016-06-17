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
						'client_id' => '59422c0c7805f214ea5b4ad0dd7ae98f140f3348',
						'client_secret' => '04addf25b054a1e0b2b0be994c69b2c199a89663',
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