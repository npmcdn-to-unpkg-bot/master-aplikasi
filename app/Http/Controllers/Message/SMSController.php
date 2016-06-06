<?php
namespace App\Http\Controllers\Message;
use App\Http\Controllers\Controller;
use App\Classes\Message\MessageClass;
use DB;
use Auth;
use Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;

class SMSController extends Controller
{
	public function __construct()
	{
		
    	$this->middleware('auth');
	}
	
	public function getIndex()
	{
		$user = Auth::user();
    	return view('message.inbox')->with('user',$user);
	}
	
	
	public function getData(Request $request)
	{
		function query($user)
		{
			$results = DB::table('msg_messages')
					  ->select(DB::Raw('max(date) as tanggal'))
					  ->where('idUser',$user)
					  ->where('archived',0)
					  ->groupBy('address')
					  ->get();
			$array = array();
			foreach($results as $result)
			{
				$array[] = $result->tanggal;
			}
			return $array;
		}
		
		$user = Auth::user();
		
		$smss = DB::table('msg_messages')->select('msg_messages.id','msg_messages.address','msg_contacts.nama','msg_messages.body','msg_messages.date','msg_messages.type')
				->leftJoin('msg_contacts', 'msg_messages.address', '=', 'msg_contacts.phone')
				->where('msg_messages.idUser',$user->id)
				->where('msg_messages.archived',0)
				->whereIn('msg_messages.date',query($user->id))
				->groupBy('msg_messages.address')
				->groupBy('msg_contacts.nama')
				->groupBy('msg_messages.id')
				->orderBy('date','desc');
		
		
        return Datatables::of($smss)
		->filterColumn('body', function($query, $keyword) {
                $query->where("msg_messages.address", 'like',"%{$keyword}%")->orWhere("msg_messages.body", 'like',"%{$keyword}%");
            })
		->addColumn('body', function ($sms) {
				
				$namacontact = $sms->nama;
				if($namacontact=="") $namacontact = $sms->address;
				$name = '<a href="/message/inbox/detail/'.$sms->id.'"><b>'.$namacontact .'</b></a>';
				$body = preg_replace( "/\r|\n/", "",$sms->body);
				if($sms->type==2) $body = "You : ". $body;
				$tanggal = '<div style="font-size:11px;white-space: nowrap;color:#999999">'.tglIndo(strtotime($sms->date),"z",7) .'</div>';
				
				if(MessageClass::haveUnread($sms->address)>0)
				{
					$warna = ' style=\"background-color:#BAE3F8;\"';
				}
				else
				{
					$warna = "";
				}
				
				$data = '<div'.$warna.'>'. $name .'<br />'.$body.'<br />'.$tanggal.'</div>';
				
				return $data;
            })
		
		->addColumn('action', function ($sms) {
                return '<button id="btn-del" onClick="hapus(\''. $sms->address .'\')" type="button" class="btn btn-danger btn-sm"><b class="fa fa-trash-o"> Delete </b></button>';
            })
		
		
		->make(true);
		
	}
	
	public function postImportData()
	{
		$ret = array();
		$user = Auth::user();
		$destinationPath = "../storage/logs/";
		$fileName = date('YmdHis') .".xml";
		if (Input::hasFile('myfile'))
		{
			$file_temp = $destinationPath.$fileName;
			Input::file('myfile')->move($destinationPath, $fileName);
			
			$xml = simplexml_load_file($file_temp);
			foreach($xml as $xmldata)
			{
				$cek = DB::table('msg_messages')
					   ->select('id')
					   ->where('address',$xmldata->attributes()->address)
					   ->where('date',MessageClass::waktu($xmldata->attributes()->date))
					   ->where('body',$xmldata->attributes()->body)
					   ->where('idUser',$user->id)
					   ->count();
			    
				
				if($cek==0)
				{
					DB::table('msg_messages')
					->insert([
					[
					'address'=>$xmldata->attributes()->address,
					'date'=>MessageClass::waktu($xmldata->attributes()->date),
					'body'=>$xmldata->attributes()->body,
					'type'=>$xmldata->attributes()->type,
					'date_sent'=>MessageClass::waktu($xmldata->attributes()->date_sent),
					'read'=>$xmldata->attributes()->read,
					'readable_date'=>MessageClass::waktu2($xmldata->attributes()->readable_date),
					'idUser'=>$user->id,
					'archived'=>0
					]
					]);	
				}
				
				
			}
			
			unlink($file_temp);
		}
		echo json_encode($ret);
	}
	
	public function getImportData()
	{
		return view('message/inbox-import');
	}
	
	public function getSearch(Request $request)
	{
		
		
		$q = $request->input('q');
		$user = Auth::user();
		if($q!="")
		{
			$hasil = "";
			$term = trim(strip_tags($q));
			
			$kondisi = (DB::getConfig('driver') == "pgsql" ? "ILIKE" : "LIKE");
			
			$result = DB::table('msg_contacts')
					  ->select('nama','phone')
					  ->where('idUser',$user->id)
					  ->where(function($query) use ($term,$kondisi){
						  $query->where('nama',$kondisi,'%'.strtolower($term).'%')
						        ->orWhere('phone',$kondisi,'%'.strtolower($term).'%');
						  })
					  ->get();
			
			foreach($result as $rs)
			{
				if($rs->nama==$rs->phone)
				{
					$hasil .= '{ "value": "'.$rs->nama.'", "data": "'.$rs->phone.'" },';
				}
				else
				{
					$hasil .= '{ "value": "'.$rs->nama.' ('.$rs->phone.')", "data": "'.$rs->phone.'" },';
				}
			}
			
			$hasil = rtrim($hasil, ",");
			
			print('{"query": "Unit","suggestions": ['. $hasil ."]}");
			
		}
	}
	
	public function postSend(Request $request)
	{
		$user = Auth::user();
		$to = $request->input('to');
		$text = $request->input('text');
		$service = $request->input('service');
		$strError = "";
		if($to=="") $strError .= "<li>To tidak boleh kosong</li>";
		if($text=="") $strError .= "<li>Text tidak boleh kosong</li>";
		if($service=="") $strError .= "<li>Service tidak boleh kosong</li>";
		if($strError=="")
		{
			$to = MessageClass::FormatNumber($to);
			$hasil = array();
			$hasil = MessageClass::SendMessage("",$to,$text,$service);	
			if($hasil['status']=="success")
			{
				print("success|". $hasil['id']);
			}
			else
			{
				$strError .= "<li>Provider tidak ditemukan</li>";
			}
		}
		else
		{
			print('error|<div class="alert alert-danger">
					<ul>
						'.$strError.'           					
					</ul>
				</div>');
		}
	}
	
	public function getSend(Request $request)
	{
		$user = Auth::user();
		$services = DB::table('msg_accounts')
					->where('idUser',$user->id)
					->get();
		$to = $request->input('to');
		$to = urlencode($to);
		return view('message/send')->with('user',$user)->with('services',$services)->with('to',$to);
	}
	
	public function getDeleteData($id)
	{
		$user = Auth::user();
		DB::table('msg_messages')->where('id',$id)->where('idUser',$user->id)->update(['archived'=>1]);
	}
	
	public function getInboxDetail($id)
	{
		$user = Auth::user();
		
		$address = DB::table('msg_messages')
				->select('address')
				->where('id',$id)
				->where('idUser',$user->id)
				->first();
		
		if(count($address)<1)
		{
			Redirect::to('/message/inbox')->send();
		}
		
		$services = DB::table('msg_accounts')
					->where('idUser',$user->id)
					->get();
				
		DB::table('msg_messages')
		->where('address',$address->address)
		->where('idUser',$user->id)
		->where('read',0)
		->update(['read'=>1,'readable_date'=>date('Y-m-d H:i:s')]);
		
		$result = DB::table('msg_messages')
				  ->where('address',$address->address)
				  ->where('idUser',$user->id)
				  ->where('archived',0)
				  ->orderBy('date','ASC')
				  ->get();
		
		
		
		$count = 0;
		$data = array();
		foreach($result as $sms)
		{
			$data[$count]['id'] = $sms->id;
			$data[$count]['address'] = $sms->address;
			$data[$count]['phone'] = $sms->phone;
			$data[$count]['body'] = $sms->body;
			$data[$count]['type'] = $sms->type;
			$data[$count]['date'] = tglIndo(strtotime($sms->date),"z",7);
			$count++;
		}
			  	
		$from = MessageClass::NameContact($address->address);
		$jml = count($data);
		return view('message/inbox-detail')
			   ->with('user',$user)
			   ->with('from',$from)
			   ->with('data',$data)
			   ->with('jml',$jml)
			   ->with('address',$address)
			   ->with('services',$services);
	}
	
	public function getDelMessage($address)
	{
		$user = Auth::user();
		DB::table('msg_messages')
		->where('idUser',$user->id)
		->where('address',$address)
		->update(['archived'=>1]);
	}
	
	
}
?>
