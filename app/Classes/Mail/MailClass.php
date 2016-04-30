<?php
namespace App\Classes\Mail;
use Auth;
use DB;

   class MailClass {
	   public static function MailHeader($string)
	   {
			$header_arr = array();
			$test = json_decode($string);
			for($i=0;$i<count($test);$i++)
			{
				$header_arr[$test[$i][0]] = $test[$i][1];
			}
			return $header_arr;   
	   }
	   
	   public static function setConf($name,$value,$idUser="")
		{
			if($idUser=="")
			{
				$user = Auth::user();
				$idUser = $user->id;	
			}
			
			$result = DB::table('mail_settings')
					  ->select('value')
			          ->where('name',$name)
					  ->where('idUser',$idUser)
					  ->first();
			
			if(empty($result))
			{
				$result = DB::table('mail_settings')->insert([
				['name'=>$name,'idUser'=>$idUser]
				]);
			}
			
			if($value=="" && $name!="")
			{
				DB::table('mail_settings')
				->where('idUser',$idUser)
				->where('name',$name)
				->delete();
			}
			else
			{
				DB::table('mail_settings')
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
			$result = DB::table('mail_settings')
					  ->select('value')
			          ->where('name',$name)
					  ->where('idUser',$idUser)
					  ->first();
			
			if(empty($result))
			{
				$result = DB::table('mail_settings')->insert([
				['name'=>$name,'idUser'=>$idUser]
				]);
			}
			else
			{
				$value = $result->value;
			}
    		return $value;
		}
   }
?>