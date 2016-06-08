<?php
namespace App\Http\Controllers\Mail;
use App\Http\Controllers\Controller;
use App\Classes\Mail\MailClass;
use DB;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class AccountController extends Controller
{
	public function __construct()
	{
		
    	$this->middleware('auth');
	}
	
	public function getIndex()
	{
		$user = Auth::user();
    	return view('mail.account')->with('user',$user);
	}
	
	
	public function getData()
	{
		$user = Auth::user();
		$accounts = DB::table('mail_accounts')->select(['name', 'email', 'id'])->where('idUser',$user->id);
		
        return Datatables::of($accounts)
		->addColumn('action', function ($account) {
                return '<button id="btn-edit" onClick="editAccount('.$account->id.')" type="button" class="btn btn-success btn-sm"><b class="fa fa-pencil"> Edit </b></button>&nbsp;<button id="btn-del" onClick="hapus('.$account->id.')" type="button" class="btn btn-danger btn-sm"><b class="fa fa-trash-o"> Delete </b></button>';
            })
		->make(true);
	}
	
	public function postAddData(Request $request)
	{
		$user = Auth::user();
		$name = $request->input('name');
		$email = $request->input('email');
		$strError = "";
		if($name=="") $strError .= "<li>Nama tidak boleh kosong</li>";
		if($email=="") $strError .= "<li>Email tidak boleh kosong</li>";
		if($strError=="")
		{
			//MessageClass::InsertContact($nama,$phone,$user->id);
			DB::table('mail_accounts')->insert(['name'=>$name,'email'=>$email,'idUser'=>$user->id]);
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
	
	public function getAddData()
	{
		return view('mail/account-tambah');
	}
	
	public function getEditData($id)
	{
		$user = Auth::user();
		$account = DB::table('mail_accounts')->where('id',$id)->where('idUser',$user->id)->first();
		return view('mail/account-edit')->with('account',$account);
	}
	
	public function postEditData(Request $request)
	{
		$user = Auth::user();
		$name = $request->input('name');
		$id = $request->input('id');
		$email = $request->input('email');
		$strError = "";
		if($id=="") $strError .= "<li>Missing ID</li>";
		if($name=="") $strError .= "<li>Nama tidak boleh kosong</li>";
		if($email=="") $strError .= "<li>email tidak boleh kosong</li>";
		if($strError=="")
		{
			DB::table('mail_accounts')->where('id',$id)->where('idUser',$user->id)->update(['name'=>$name,'email'=>$email]);
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
	
	public function getDeleteData($id)
	{
		$user = Auth::user();
		DB::table('mail_accounts')->where('id',$id)->where('idUser',$user->id)->delete();
	}
}
?>