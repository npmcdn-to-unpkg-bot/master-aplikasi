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
				   ->where('status',1)
				   ->orderBy('tanggal','desc')
				   ->first();
		
		if(!count($last)) return Redirect('/auth/login');
		
		$last_attachment = DB::table('blog_attachments')->where('post_id',$last->id)->orderBy('sort','asc')->first();
		
		
		$results = \App\Models\Blog\blog_posts::with(array('attachments' => function($query)
				   {
					   $query->orderBy('sort', 'asc');
				   }
				   ))
				   ->where('tipe_konten','gallery')
				   ->where('idUser',1)
				   ->where('status',1)
				   ->orderBy('tanggal','desc')
				   ->paginate(6);
		
		
		if(isset($_SERVER['HTTP_CF_VISITOR'])) $results->setPath(secure_url('')."/");
		
		if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']))
		{
			if($_SERVER['HTTP_X_FORWARDED_PROTO']=="https") $results->setPath(secure_url('')."/");
		}
		
		$url = Request::url();
		
		$stdClass = app();
    	$setting = $stdClass->make('stdClass');
		$setting->judul1 = BlogClass::getConf('judul1',1);
		if($setting->judul1=="") $setting->judul1 = str_ireplace("www.","",$_SERVER['HTTP_HOST']);
		$setting->judul2 = BlogClass::getConf('judul2',1);
		$setting->deskripsi = BlogClass::getConf('deskripsi',1);
		$setting->facebook_deskripsi = BlogClass::countMoments(1);
		$setting->header_url = BlogClass::getConf('header_url',1);
		$setting->facebook = BlogClass::getConf('facebook',1);
		$setting->twitter = BlogClass::getConf('twitter',1);
		$setting->instagram = BlogClass::getConf('instagram',1);
		$setting->github = BlogClass::getConf('github',1);
		$setting->path = BlogClass::getConf('path',1);
		$setting->url = $url;
		$setting->image = $last_attachment->secure_url;
		$setting->title = $setting->judul2;
		
		return view('blog.frontend.timeline')->with('setting',$setting)->with('results',$results);
	}
	
	
	public function getSingle($id,Request $request)
	{
		
		
		$last = \App\Models\Blog\blog_posts::with(array('attachments' => function($query)
				   {
					   $query->orderBy('sort', 'asc');
				   }
				   ))
				   ->where('tipe_konten','gallery')
				   ->where('idUser',1)
				   ->where('slug',$id)
				   ->orderBy('tanggal','desc')
				   ->first();
		
		if(!isset($last)) return redirect("/");
		
		$last_attachment = DB::table('blog_attachments')->where('post_id',$last->id)->orderBy('sort','asc')->first();
		
		$url = Request::url();
		
		$stdClass = app();
    	$setting = $stdClass->make('stdClass');
		$setting->judul1 = BlogClass::getConf('judul1',1);
		$setting->judul2 = BlogClass::getConf('judul2',1);
		$setting->header_url = BlogClass::getConf('header_url',1);
		$setting->facebook = BlogClass::getConf('facebook',1);
		$setting->twitter = BlogClass::getConf('twitter',1);
		$setting->instagram = BlogClass::getConf('instagram',1);
		$setting->url = $url;
		$setting->image = $last_attachment->secure_url;
		$setting->title = $last->judul ." ". $setting->judul1;
		$setting->facebook_deskripsi = BlogClass::countMoments(1);
		if(empty($last->konten))
		{
			$setting->deskripsi = BlogClass::getConf('deskripsi',1);	
		}
		else
		{
			$setting->deskripsi = $last->konten;
		}
		return view('blog.frontend.timeline-single')->with('setting',$setting)->with('last',$last)->with('last_attachment',$last_attachment);
	}
	
	
}
?>