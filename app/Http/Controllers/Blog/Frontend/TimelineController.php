<?php
namespace App\Http\Controllers\Blog\Frontend;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Request;
use App\Classes\Blog\BlogClass;
use Redirect;

class TimelineController extends Controller
{
	public function __construct()
	{
    	
	}
	
	public function getIndex(Request $request)
	{
		
		
		$last = \App\Models\Blog\blog_posts::with('attachments')
				   ->where('tipe_konten','gallery')
				   ->where('idUser',1)
				   ->orderBy('tanggal','desc')
				   ->first();
		
		if(!count($last)) return Redirect('/auth/login');
		
		$last_attachment = DB::table('blog_attachments')->where('post_id',$last->id)->orderBy('id','asc')->first();
		
		
		$results = \App\Models\Blog\blog_posts::with('attachments')
				   ->where('tipe_konten','gallery')
				   ->where('idUser',1)
				   ->orderBy('tanggal','desc')
				   ->paginate(6);
		
		$results->setPath(secure_url('')."/");
		
		$url = Request::url();
		
		$stdClass = app();
    	$setting = $stdClass->make('stdClass');
		$setting->judul1 = BlogClass::getConf('judul1',1);
		$setting->judul2 = BlogClass::getConf('judul2',1);
		$setting->deskripsi = BlogClass::getConf('deskripsi',1);
		$setting->header_url = BlogClass::getConf('header_url',1);
		$setting->facebook = BlogClass::getConf('facebook',1);
		$setting->twitter = BlogClass::getConf('twitter',1);
		$setting->instagram = BlogClass::getConf('instagram',1);
		$setting->github = BlogClass::getConf('github',1);
		$setting->url = $url;
		$setting->image = $setting->header_url;
		$setting->title = $setting->judul2;
		
		return view('blog.frontend.timeline')->with('setting',$setting)->with('results',$results);
	}
	
	
	public function getSingle($id,Request $request)
	{
		
		
		$last = \App\Models\Blog\blog_posts::with('attachments')
				   ->where('tipe_konten','gallery')
				   ->where('idUser',1)
				   ->where('slug',$id)
				   ->orderBy('tanggal','desc')
				   ->first();
		
		
		$last_attachment = DB::table('blog_attachments')->where('post_id',$last->id)->orderBy('id','asc')->first();
		
		$url = Request::url();
		
		$stdClass = app();
    	$setting = $stdClass->make('stdClass');
		$setting->judul1 = BlogClass::getConf('judul1',1);
		$setting->judul2 = BlogClass::getConf('judul2',1);
		$setting->deskripsi = BlogClass::getConf('deskripsi',1);
		$setting->header_url = BlogClass::getConf('header_url',1);
		$setting->facebook = BlogClass::getConf('facebook',1);
		$setting->twitter = BlogClass::getConf('twitter',1);
		$setting->instagram = BlogClass::getConf('instagram',1);
		$setting->url = $url;
		$setting->image = $last_attachment->secure_url;
		$setting->title = $last->judul ." ". $setting->judul1;
		
		return view('blog.frontend.timeline-single')->with('setting',$setting)->with('last',$last)->with('last_attachment',$last_attachment);
	}
	
	
}
?>