<?php
namespace App\Http\Controllers\Blog\Backend;
use App\Classes\Classes;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Redirect;
use File;

class SettingController extends Controller
{
	public function __construct()
	{
    	$this->middleware('auth');
	}
		
	public function getSetting()
	{
		$user = Auth::user();
		$stdClass = app();
    	$setting = $stdClass->make('stdClass');
		$setting->judul1 = Classes::getConf('judul1');
		$setting->judul2 = Classes::getConf('judul2');
		$setting->deskripsi = Classes::getConf('deskripsi');
		$setting->header_public = Classes::getConf('header_public');
		$setting->header_url = Classes::getConf('header_url');
		$setting->facebook = Classes::getConf('facebook');
		$setting->twitter = Classes::getConf('twitter');
		$setting->instagram = Classes::getConf('instagram');
		$key=md5(date('YmdHis'));
		return view('blog.backend.setting')->with('user',$user)->with('setting',$setting)->with('key',$key);
	}
	
	public function postSetting(Request $request)
	{
		$user = Auth::user();
		$key = $request->input('key');
		Classes::setConf('judul1',$request->input('judul1'));
		Classes::setConf('judul2',$request->input('judul2'));
		Classes::setConf('deskripsi',$request->input('deskripsi'));
		Classes::setConf('facebook',$request->input('facebook'));
		Classes::setConf('twitter',$request->input('twitter'));
		Classes::setConf('instagram',$request->input('instagram'));
		$result = DB::table('blog_tmp')->where('key',$key)->where('idUser',$user->id)->first();
		if (count($result))
		{
			\Cloudinary::config(array( 
  				"cloud_name" => env('CLOUDINARY_NAME'), 
  				"api_key" => env('CLOUDINARY_KEY'), 
  				"api_secret" => env('CLOUDINARY_SECRET') 
			));
			
			$header_public = Classes::getConf('header_public');
			
			if($header_public!="")
			{
				\Cloudinary\Uploader::destroy($header_public);
			}
			
			$cloudinary = \Cloudinary\Uploader::upload($result->file);
			
			Classes::setConf('header_public',$cloudinary['public_id']);
			Classes::setConf('header_url',$cloudinary['secure_url']);
			
			DB::table('blog_tmp')->where('key',$key)->where('file',$result->file)->where('idUser',$user->id)->delete();
			unlink($result->file);
		}
			
		
		return Redirect('/blog/setting');
	}
	
}
?>