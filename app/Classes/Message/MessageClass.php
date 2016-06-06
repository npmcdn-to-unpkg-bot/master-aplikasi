<?php
namespace App\Classes\Message;
use Services_Twilio;
use Telerivet_API;
use Auth;
use DB;

   class MessageClass {
	   
	    public static function waktu($timestamp)
		{
			$timestamp = (string)$timestamp;
			$date = date("Y-m-d H:i:s", $timestamp/1000);
			return $date;
		}
		
		public static function waktu2($timestamp)
		{
			$timestamp = (string)$timestamp;
			$date = date("Y-m-d H:i:s", strtotime($timestamp));
			return $date;
		}
		
	    public static function getConf($name,$idUser="")
		{
			if($idUser=="")
			{
				$user = Auth::user();
				$idUser = $user->id;	
			}
			$value = "";
			$result = DB::table('msg_settings')
					  ->select('value')
			          ->where('name',$name)
					  ->where('idUser',$idUser)
					  ->first();
			
			if(empty($result))
			{
				$result = DB::table('msg_settings')->insert([
				['name'=>$name,'idUser'=>$idUser]
				]);
			}
			else
			{
				$value = $result->value;
			}
    		return $value;
		}
		
		public static function setConf($name,$value,$idUser="")
		{
			if($idUser=="")
			{
				$user = Auth::user();
				$idUser = $user->id;	
			}
			
			$result = DB::table('msg_settings')
					  ->select('value')
			          ->where('name',$name)
					  ->where('idUser',$idUser)
					  ->first();
			
			if(empty($result))
			{
				$result = DB::table('msg_settings')->insert([
				['name'=>$name,'idUser'=>$idUser]
				]);
			}
			
			if($value=="" && $name!="")
			{
				DB::table('msg_settings')
				->where('idUser',$idUser)
				->where('name',$name)
				->delete();
			}
			else
			{
				DB::table('msg_settings')
				->where('idUser',$idUser)
				->where('name',$name)
				->update(['value'=>$value]);
			}
		}
		
		public static function haveUnread($address)
		{
			$user = Auth::user();
			$jml = DB::table('msg_messages')
				   ->where('idUser',$user->id)
				   ->where('read',0)
				   ->where('address',$address)
				   ->where('type',1)
				   ->count();
			return $jml;	
		}
		
        public static function InsertContact($nama="",$number="",$idUser="") {
			
            if($idUser=="")
			{
				$user = Auth::user();
				$idUser = $user->id;	
			}
			if($nama=="") $nama = $number;
			$number = MessageClass::FormatNumber($number);
			$rs = DB::table('msg_contacts')->where('phone',$number)->where('idUser',$idUser)->first();
			if(count($rs)==0)
			{
				DB::table('msg_contacts')->insert(['phone'=>$number,'nama'=>$nama,'idUser'=>$idUser]);
			}
			else if($number==$rs->phone)
			{
				DB::table('msg_contacts')->where('phone',$number)->where('idUser',$idUser)->update(['nama'=>$nama]);
			}
        }
		
		
		public static function NameContact($number,$idUser="")
		{
			if($idUser=="")
			{
				$user = Auth::user();
				$idUser = $user->id;	
			}
			$result = DB::table('msg_contacts')
			          ->select('nama')
					  ->where('phone',$number)
					  ->where('idUser',$idUser)
					  ->first();
			if(count($result)>0)
			{
				$number = $result->nama;
			}
			return $number;
		}
		
		
		public static function DeleteContact($id="",$idUser="")
		{
			if($idUser=="")
			{
				$user = Auth::user();
				$idUser = $user->id;	
			}
			if($id!="")
			{
				DB::table('msg_contacts')->where('id',$id)->where('idUser',$idUser)->delete();
			}
		}
		
		public static function FormatNumber($number)
		{
			if($number!="")
			{
				$number = str_ireplace(" ","",$number);
				$number = str_ireplace("-","",$number);
				$number = str_ireplace("+","",$number);
				$check = substr($number,0,1);
				if($check=="0") $number = "62". substr($number,1);
				$check = substr($number,0,1);
				if($check!="+") $number = "+". $number;
			}
			return $number;
		}
		
		public static function getIdUserByPhone($phone)
		{
			$cek = DB::table('msg_accounts')
				   ->select('idUser')
				   ->where('phone',$phone)->first();
			
			$aaa = "";
			if(count($cek)>0)
			{
  				$aaa = $cek->idUser;
			}
  			
			return $aaa;
		}
		
		public static function OutputvCard(vCard $vCard)
		{
			
			foreach ($vCard -> N as $Name)
			{
				
			}
			
			if ($vCard -> TEL)
			{
			
				foreach ($vCard -> TEL as $Tel)
				{
					$nama = $Name['FirstName'].' '.$Name['LastName'];
					if (is_scalar($Tel))
					{
						$phone = MessageClass::FormatNumber($Tel);
					}
					else
					{
						$phone = MessageClass::FormatNumber($Tel['Value']);
					}
					
					MessageClass::InsertContact($nama,$phone);
				}
			
			}

		}
		
		public static function SendMessage($from="",$to="",$text="",$id="",$idUser="")
		{
			$to = MessageClass::FormatNumber($to);
			if($idUser=="")
			{
				$user = Auth::user();
				$idUser = $user->id;	
			}
			$result = DB::table('msg_accounts')
					  ->where('id',$id)
					  ->where('idUser',$idUser)
					  ->first();
			
			$api_key = $result->api_key;
			$api_secret = $result->api_secret;
			$phone = $result->phone;
			$service = $result->service;
			
			$arr_result = array();
			
			switch ($service) {
    		case "Nexmo":
        		if($from=="") $from = $phone;
				$fields_string="";
				$from = str_replace("+","",$from);
				$to = str_replace("+","",$to);
				$url = 'https://rest.nexmo.com/sms/json';
				$fields = array(
						'api_key' => urlencode($api_key),
						'api_secret' => urlencode($api_secret),
						'from' => urlencode($from),
						'to' => urlencode($to),
						'text' => urlencode($text)
				);
				
				foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
				rtrim($fields_string, '&');

				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL, $url);
				curl_setopt($ch,CURLOPT_POST, count($fields));
				curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
				curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
				curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
				
				
				$result = curl_exec($ch);
				
				curl_close($ch);
				
				$from = "+".$from;
				$to = "+".$to;
				$arr_result['status'] = "success";
				
        		break;
    	case "Telerivet":
        		if($from=="") $from = $phone;
				$tr = new Telerivet_API($api_key);
				$project = $tr->initProjectById($api_secret);
				$sent_msg = $project->sendMessage(array(
    			'content' => $text,
    			'to_number' => $to
				));
				$arr_result['status'] = "success";
        		break;
    	case "Twilio":
        		if($from=="") $from = $phone;
				$sid = $api_key;
				$token = $api_secret;
				$client = new Services_Twilio($sid, $token);
				$sms = $client->account->sms_messages->create($from, $to, $text, array());
				$arr_result['status'] = "success";
        		break;
		default:
				$arr_result['status'] = "failed";
				break;
		}
		if($arr_result['status']=="success")
		{
			$lastid = DB::table('msg_messages')->insertGetId([
			'address'=>$to,
			'date'=>date('Y-m-d H:i:s'),
			'body'=>$text,
			'type'=>2,
			'date_sent'=>date('Y-m-d H:i:s'),
			'readable_date'=>date('Y-m-d H:i:s'),
			'read'=>0,
			'archived'=>0,
			'phone'=>$from,
			'idUser'=>$idUser
			]);
			
			$arr_result['id'] = $lastid;
		}
		$arr_result['from'] = $from;
		$arr_result['to'] = $to;
		$arr_result['text'] = $text;
		return $arr_result;
		}
		
    }

?>