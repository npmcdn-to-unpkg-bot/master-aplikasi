<?php
namespace App\Http\Controllers\Blog\Backend;
use App\Classes\Blog\BlogClass;
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
		$setting->judul1 = BlogClass::getConf('judul1');
		$setting->judul2 = BlogClass::getConf('judul2');
		$setting->deskripsi = BlogClass::getConf('deskripsi');
		$setting->header_public = BlogClass::getConf('header_public');
		$setting->header_url = BlogClass::getConf('header_url');
		$setting->facebook = BlogClass::getConf('facebook');
		$setting->twitter = BlogClass::getConf('twitter');
		$setting->instagram = BlogClass::getConf('instagram');
		$setting->github = BlogClass::getConf('github');
		$key=md5(date('YmdHis'));
		return view('blog.backend.setting')->with('user',$user)->with('setting',$setting)->with('key',$key);
	}
	
	public function postSetting(Request $request)
	{
		$user = Auth::user();
		$key = $request->input('key');
		BlogClass::setConf('judul1',$request->input('judul1'));
		BlogClass::setConf('judul2',$request->input('judul2'));
		BlogClass::setConf('deskripsi',$request->input('deskripsi'));
		BlogClass::setConf('facebook',$request->input('facebook'));
		BlogClass::setConf('twitter',$request->input('twitter'));
		BlogClass::setConf('instagram',$request->input('instagram'));
		BlogClass::setConf('github',$request->input('github'));
		$result = DB::table('blog_tmp')->where('key',$key)->where('idUser',$user->id)->first();
		if (count($result))
		{
			\Cloudinary::config(array( 
  				"cloud_name" => env('CLOUDINARY_NAME'), 
  				"api_key" => env('CLOUDINARY_KEY'), 
  				"api_secret" => env('CLOUDINARY_SECRET') 
			));
			
			$header_public = BlogClass::getConf('header_public');
			
			if($header_public!="")
			{
				\Cloudinary\Uploader::destroy($header_public);
			}
			
			$cloudinary = \Cloudinary\Uploader::upload($result->file);
			
			BlogClass::setConf('header_public',$cloudinary['public_id']);
			BlogClass::setConf('header_url',$cloudinary['secure_url']);
			
			DB::table('blog_tmp')->where('key',$key)->where('file',$result->file)->where('idUser',$user->id)->delete();
			unlink($result->file);
		}
			
		
		return Redirect('/blog/setting');
	}
	
}
?>