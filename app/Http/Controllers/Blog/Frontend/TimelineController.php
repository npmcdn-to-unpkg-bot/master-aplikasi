<?php
namespace App\Http\Controllers\Blog\Frontend;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Request;

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
		
		
		$last_attachment = DB::table('blog_attachments')->where('post_id',$last->id)->orderBy('id','asc')->first();
		
		
		$results = \App\Models\Blog\blog_posts::with('attachments')
				   ->where('tipe_konten','gallery')
				   ->where('idUser',1)
				   ->orderBy('tanggal','desc')
				   ->paginate(6);
		
		$results->setPath(secure_url('')."/");
		
		$url = Request::url();
		
		return view('blog.frontend.timeline')->with('url',$url)->with('results',$results)->with('last',$last)->with('last_attachment',$last_attachment);
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
		
		return view('blog.frontend.timeline-single')->with('url',$url)->with('last',$last)->with('last_attachment',$last_attachment);
	}
	
	
}
?>