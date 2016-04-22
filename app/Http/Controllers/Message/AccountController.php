<?php
namespace App\Http\Controllers\Message;
use App\Http\Controllers\Controller;
use App\Classes\Message\MessageClass;
use DB;
use Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{
	public function __construct()
	{
		
    	$this->middleware('auth');
	}
	
	public function getAddData()
	{
		return view('message/account-tambah');
	}
	
	public function getDeleteData($id)
	{
		$user = Auth::user();
		DB::table('msg_accounts')->where('id',$id)->where('idUser',$user->id)->delete();
	}
	
	public function postAddData(Request $request)
	{
		$user = Auth::user();
		$api_key = $request->input('api_key');
		$api_secret = $request->input('api_secret');
		$service = $request->input('service');
		$phone = $request->input('phone');
		$phone = MessageClass::formatNumber($phone);
		$voice = 1;
		$strError = "";
		if($api_key=="") $strError .= "<li>Api Key tidak boleh kosong</li>";
		if($api_secret=="") $strError .= "<li>Api Secret tidak boleh kosong</li>";
		if($phone=="") $strError .= "<li>Phone tidak boleh kosong</li>";
		if($strError=="")
		{
			if($service=="Telerivet")
			{
				$voice = 0;	
			}
			DB::table('msg_accounts')->insert([
    		['phone' => $phone, 'api_key' => $api_key, 'api_secret'=>$api_secret, 'voice'=>$voice, 'idUser'=>$user->id, 'service'=>$service]
			]);
		}
		else
		{
			print('<div class="alert alert-danger">
					<ul>
						'.$strError.'           					
					</ul>
				</div>');
		}
	}
	
	public function postEditData(Request $request)
	{
		$user = Auth::user();
		$api_key = $request->input('api_key');
		$api_secret = $request->input('api_secret');
		$service = $request->input('service');
		$id = $request->input('id');
		$phone = $request->input('phone');
		$voice = 1;
		$phone = MessageClass::formatNumber($phone);
		$strError = "";
		
		if($id=="") $strError .= "<li>Missing ID</li>";
		if($api_key=="") $strError .= "<li>Api Key tidak boleh kosong</li>";
		if($api_secret=="") $strError .= "<li>Api Secret tidak boleh kosong</li>";
		if($phone=="") $strError .= "<li>Phone tidak boleh kosong</li>";
		if($strError=="")
		{
			if($service=="Telerivet")
			{
				$voice = 0;	
			}
			DB::table('msg_accounts')
			->where('id',$id)
			->where('idUser',$user->id)
			->update([
				'api_key'=>$api_key,
				'api_secret'=>$api_secret,
				'service'=>$service,
				'voice'=>$voice,
				'phone'=>$phone
			]);
		}
		else
		{
			print('<div class="alert alert-danger">
					<ul>
						'.$strError.'           					
					</ul>
				</div>');
		}
	}
	
	public function getEditData($id)
	{
		$user = Auth::user();
		$account = DB::table('msg_accounts')->where('id',$id)->where('idUser',$user->id)->first();
		return view('message/account-edit')->with('account',$account);
	}
	
	public function getListData()
	{
		
		$user = Auth::user();
		$accounts = DB::table('msg_accounts')
		->select('id','service','api_key','api_secret','phone','voice','idUser')
		->where('idUser',$user->id)
		->orderBy('id','desc')
		->get();
		
		$data = '{ "data": [';
		if(count($accounts)>0)
		{
			foreach($accounts as $accounts)
			{
				$data .= '[ "'. $accounts->service .'","'. $accounts->phone .'","'. $accounts->id .'" ],';
			}
			$data = substr($data,0,-1);
			$data .= ']}';
			print($data);
		}
		else
		{
			echo '{
    			"sEcho": 1,
    			"iTotalRecords": "0",
    			"iTotalDisplayRecords": "0",
    			"data": []
				}'; 	
		}
	}
	
	public function getData()
	{
		$user = Auth::user();
		$accounts = DB::table('msg_accounts')
		->select('id','service','api_key','api_secret','phone','voice','idUser')
		->where('idUser',$user->id)
		->orderBy('id','desc')
		->get();
		return view('message/account')->with('accounts',$accounts)->with('user',$user);
	}
}
?>