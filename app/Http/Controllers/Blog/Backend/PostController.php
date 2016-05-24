<?php
namespace App\Http\Controllers\Blog\Backend;
use App\Classes\Blog\BlogClass;
use App\Classes\Blog\SimpleImage;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use Validator;
use Redirect;
use File;

class PostController extends Controller
{
	public function __construct()
	{
    	$this->middleware('auth');
	}
		
	public function postImageDelete(Request $request)
	{
		$user = Auth::user();
		$filename = $request->input('name');
		DB::table('blog_tmp')->where('idUser',$user->id)->where('file',"../storage/logs/". $filename)->delete();
		if(file_exists("../storage/logs/". $filename))
		{
			unlink("../storage/logs/". $filename);
		}
	}
	
	public function postImageAdd(Request $request)
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
			
			if($key!="header"){
				if($width>1280)
				{
					$img = new SimpleImage($pathfile); 
					$img->fit_to_width(1280);
					$img->save($pathfile);
				}
				else if($height>1280)
				{
					$img = new SimpleImage($pathfile); 
					$img->fit_to_height(1280);
					$img->save($pathfile);
				}
			}
			
			$cek = DB::table('blog_tmp')->where('idUser',$user->id)->where('file',$pathfile)->count();
			if($cek==0)
			{
				DB::table('blog_tmp')->insert(['file'=>$pathfile,'idUser'=>$user->id,'key'=>$key]);
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
				
				if($key!="header"){
					if($width>1280)
					{
						$img = new SimpleImage($pathfile); 
						$img->fit_to_width(1280);
						$img->save($pathfile);
					}
				else if($height>1280)
					{
							$img = new SimpleImage($pathfile); 
							$img->fit_to_height(1280);
							$img->save($pathfile);
					}
				}
				
				$cek = DB::table('blog_tmp')->where('idUser',$user->id)->where('file',$pathfile)->count();
				if($cek==0)
				{
					DB::table('blog_tmp')->insert(['file'=>$pathfile,'idUser'=>$user->id,'key'=>$key]);
				}
		
	  		}
		}
		echo json_encode($ret);
	}
	
	public function getData()
	{
		$user = Auth::user();
		$posts = DB::table('blog_posts')->select(['id', 'slug', 'tanggal', 'layout'])->where('idUser',$user->id);
		
        return Datatables::of($posts)
		->addColumn('jumlah', function ($post){
                return BlogClass::jumlahFoto($post->id);
            })
		->editColumn('tanggal', function ($post){
                return tglIndo(strtotime($post->tanggal),"z",0);
            })
		->addColumn('action', function ($post) {
                return '<button id="btn-edit" type="button" onClick="window.location=\'/blog/post/edit/'. $post->id .'\'" class="btn btn-success btn-sm"><b class="fa fa-pencil"> Edit </b></button>&nbsp;<button id="btn-del" type="button" onClick="hapus(\''. $post->id .'\')" class="btn btn-danger btn-sm"><b class="fa fa-trash-o"> Delete </b></button>';
            })
		->make(true);
	}
	
	public function getIndex()
	{
		$user = Auth::user();
    	return view('blog.backend.post')->with('user',$user);
	}
	
	public function getAddPost()
	{
		$user = Auth::user();
		
		$result = DB::table('blog_tmp')->where('idUser',$user->id)->get();
		foreach($result as $rs)
		{
			if(file_exists($rs->file))
			{
				unlink($rs->file);	
			}
		}
		
		DB::table('blog_tmp')->where('idUser',$user->id)->delete();
		$tanggal = date("Y-m-d H:i:s", strtotime('+7 hours'));
		$key = md5(date('YmdHis'));
    	return view('blog.backend.post-add')->with('user',$user)->with('tanggal',$tanggal)->with('key',$key);
	}
	
	public function getEditPost($id)
	{
		$user = Auth::user();
		$result = DB::table('blog_posts')->where('idUser',$user->id)->where('id',$id)->first();
		
		$key = md5(date('YmdHis'));
		return view('blog.backend.post-edit')->with('user',$user)->with('key',$key)->with('result',$result)->with('id',$id);
	}
	
	public function postEditPost(Request $request)
	{
		
		$user = Auth::user();
		
		
		$judul =  $request->input('judul');
		$tanggal =  $request->input('tanggal');
		$idUser =  $user->id;
		$key = $request->input('key');
		$tipe_konten = $request->input('tipe_konten');
		$tipe_post = $request->input('tipe_post');
		$konten = $request->input('konten');
		$layout = $request->input('layout');
		$id = $request->input('id');
		
		if($layout=="") $layout = 1;
		
		$guid = BlogClass::makeSlug($judul,$idUser,$id);
		DB::table('blog_posts')->where('id',$id)->where('idUser',$user->id)->update([
		'judul' => $judul, 'slug' => $guid, 'konten' => $konten, 'layout' => $layout , 'tanggal' => $tanggal, 'idUser'=>$idUser, 'tipe_konten'=>$tipe_konten, 'tipe_post'=>$tipe_post
		]);
		
		
		
		if($judul=="")
		{
			$judul = date("j M Y", strtotime($tanggal));
			$guid = BlogClass::makeSlug($judul,$idUser,$id);
			DB::table('blog_posts')->where('id',$id)->update(['judul' => $judul, 'slug' => $guid]);	
		}
			
		$result = DB::table('blog_tmp')->where('key',$key)->where('idUser',$user->id)->get();
		
		foreach($result as $rs)
		{
			\Cloudinary::config(array( 
  				"cloud_name" => env('CLOUDINARY_NAME'), 
  				"api_key" => env('CLOUDINARY_KEY'), 
  				"api_secret" => env('CLOUDINARY_SECRET') 
			));
			
			$cloudinary = \Cloudinary\Uploader::upload($rs->file);
			
			
			
			DB::table('blog_attachments')
			->insert([
			'post_id'=>$nextid,
			'public_id'=> $cloudinary['public_id'],
			'version'=> $cloudinary['version'],
			'signature'=> $cloudinary['signature'],
			'width'=> $cloudinary['width'],
			'height'=> $cloudinary['height'],
			'format'=> $cloudinary['format'],
			'resource_type'=> $cloudinary['resource_type'],
			'bytes'=> $cloudinary['bytes'],
			'type'=> $cloudinary['type'],
			'etag'=> $cloudinary['etag'],
			'url'=> $cloudinary['url'],
			'secure_url'=> $cloudinary['secure_url'],
			'idUser'=> $user->id]
			);
			
			
		}
			
    	return redirect('blog/post')->with('user',$user);
	}
	
	public function postAddPost(Request $request)
	{
		
		$user = Auth::user();
		
		
		$judul =  $request->input('judul');
		$tanggal =  $request->input('tanggal');
		$idUser =  $user->id;
		$key = $request->input('key');
		$tipe_konten = $request->input('tipe_konten');
		$tipe_post = $request->input('tipe_post');
		$konten = $request->input('konten');
		$layout = $request->input('layout');
		$guid = BlogClass::makeSlug($judul,$idUser);
		
		if($layout=="") $layout = 1;
		
		$nextid = DB::table('blog_posts')->insertGetId(
    		['judul' => $judul, 'slug'=>$guid, 'konten' => $konten, 'layout' => $layout , 'tanggal' => $tanggal, 'idUser'=>$idUser, 'tipe_konten'=>$tipe_konten, 'tipe_post'=>$tipe_post]
			);
		
		
		
		if($judul=="")
		{
			$judul = date("j M Y", strtotime($tanggal));
			$guid = BlogClass::makeSlug($judul,$idUser);
			DB::table('blog_posts')->where('id',$nextid)->update(['judul' => $judul, 'slug' => $guid]);	
		}
			
		$result = DB::table('blog_tmp')->where('key',$key)->where('idUser',$user->id)->get();
		
		// ====================================================================================
		$path = DB::table('blog_access')->where('account','path')->where('idUser',$user->id)->first();
		$authorization = "Authorization: Bearer ". $path->access_token;
		// ====================================================================================
		
		
		foreach($result as $rs)
		{
			
			\Cloudinary::config(array( 
  				"cloud_name" => env('CLOUDINARY_NAME'), 
  				"api_key" => env('CLOUDINARY_KEY'), 
  				"api_secret" => env('CLOUDINARY_SECRET') 
			));
			
			$cloudinary = \Cloudinary\Uploader::upload($rs->file);
			
			DB::table('blog_attachments')
			->insert([
			'post_id'=>$nextid,
			'public_id'=> $cloudinary['public_id'],
			'version'=> $cloudinary['version'],
			'signature'=> $cloudinary['signature'],
			'width'=> $cloudinary['width'],
			'height'=> $cloudinary['height'],
			'format'=> $cloudinary['format'],
			'resource_type'=> $cloudinary['resource_type'],
			'bytes'=> $cloudinary['bytes'],
			'type'=> $cloudinary['type'],
			'etag'=> $cloudinary['etag'],
			'url'=> $cloudinary['url'],
			'secure_url'=> $cloudinary['secure_url'],
			'idUser'=> $user->id]
			);
			
			DB::table('blog_tmp')->where('key',$key)->where('file',$rs->file)->where('idUser',$user->id)->delete();
			unlink($rs->file);
			
			// ====================================================================================
				$url = 'https://partner.path.com/1/moment/photo';
				$string_path = '{ "source_url": "'.$cloudinary['secure_url'].'", "caption": "'.$judul.' - '.secure_url('').'", "private": true }';
				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL, $url);
				curl_setopt($ch,CURLOPT_POST, count($string_path));
				curl_setopt($ch,CURLOPT_POSTFIELDS, $string_path);
				curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
				curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				curl_close($ch);
			// ====================================================================================
		}
				
    	return redirect('/blog/post')->with('user',$user);
	}
	
	public function getDeleteData($id)
	{
		$user = Auth::user();
		$result = DB::table('blog_attachments')->where('post_id',$id)->where('idUser',$user->id)->get();
		foreach($result as $rs)
		{
				\Cloudinary::config(array( 
  					"cloud_name" => env('CLOUDINARY_NAME'), 
  					"api_key" => env('CLOUDINARY_KEY'), 
  					"api_secret" => env('CLOUDINARY_SECRET') 
				));
				
				\Cloudinary\Uploader::destroy($rs->public_id);
				
				
		}
		DB::table('blog_posts')->where('id',$id)->where('idUser',$user->id)->delete();
	}
	
}
?>