<?php
namespace App\Classes;
use Auth;
use DB;

class Classes {
	public static function jumlahFoto($id)
	{
		return DB::table('blog_attachments')->where('post_id',$id)->count();
	}
	
	public static function setConf($name,$value,$idUser="")
		{
			if($idUser=="")
			{
				$user = Auth::user();
				$idUser = $user->id;	
			}
			
			$result = DB::table('blog_settings')
					  ->select('value')
			          ->where('name',$name)
					  ->where('idUser',$idUser)
					  ->first();
			
			if(empty($result))
			{
				$result = DB::table('blog_settings')->insert([
				['name'=>$name,'idUser'=>$idUser]
				]);
			}
			
			if($value=="" && $name!="")
			{
				DB::table('blog_settings')
				->where('idUser',$idUser)
				->where('name',$name)
				->delete();
			}
			else
			{
				DB::table('blog_settings')
				->where('idUser',$idUser)
				->where('name',$name)
				->update(['value'=>$value]);
			}
		}
	
	public static function getConf($name,$idUser="")
		{
			if($idUser=="")
			{
				$user = Auth::user();
				$idUser = $user->id;	
			}
			$value = "";
			$result = DB::table('blog_settings')
					  ->select('value')
			          ->where('name',$name)
					  ->where('idUser',$idUser)
					  ->first();
			
			if(empty($result))
			{
				$result = DB::table('blog_settings')->insert([
				['name'=>$name,'idUser'=>$idUser]
				]);
			}
			else
			{
				$value = $result->value;
			}
    		return $value;
		}
		
	public static function makeSlug($string,$idUser,$id="")
		{
			if($id=="") $id = 0;
			$string = str_slug($string,"-");
			$cek = 1;
			$string_test = $string;
			$i = 2;
			while($cek==1)
			{
				
				/*
				
				$users = DB::table('users')
                     ->select(DB::raw('count(*) as user_count, status'))
                     ->where('status', '<>', 1)
                     ->groupBy('status')
                     ->get();
				
				*/
				
				$results = DB::table('blog_posts')
						   ->where('idUser',$idUser)
						   ->where('slug',$string_test)
						   ->where('id','<>',$id)
						   ->count();
						   
				if($results==0)
				{
					$cek=0;	
				}
				else
				{
					$string_test = $string ."-". $i;
				}
				$i++;
			}
			return $string_test;
			
		}
}
?>