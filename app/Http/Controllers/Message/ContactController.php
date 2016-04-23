<?php
namespace App\Http\Controllers\Message;
use App\Http\Controllers\Controller;
use App\Classes\Message\MessageClass;
use App\Classes\Message\vCard;
use DB;
use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ContactController extends Controller
{
	public function __construct()
	{
		
    	$this->middleware('auth');
	}
	
	public function getIndex()
	{
		$user = Auth::user();
    	return view('message.contact')->with('user',$user);
	}
	
	
	public function getData()
	{
		$user = Auth::user();
		$contacts = DB::table('msg_contacts')->select(['nama', 'phone', 'id'])->where('idUser',$user->id);
		
        return Datatables::of($contacts)
		->addColumn('action', function ($contact) {
                return '<button id="btn-send" onClick="sendSMS(\''. $contact->phone .'\');" type="button" class="btn btn-primary btn-sm"><b class="fa fa-edit"> Send SMS </b></button>&nbsp;<button id="btn-edit" onClick="editContact('.$contact->id.')" type="button" class="btn btn-success btn-sm"><b class="fa fa-pencil"> Edit </b></button>&nbsp;<button id="btn-del" onClick="hapus('.$contact->id.')" type="button" class="btn btn-danger btn-sm"><b class="fa fa-trash-o"> Delete </b></button>';
            })
		->make(true);
	}
	
	public function getDeleteData($id)
	{
		$user = Auth::user();
		DB::table('msg_contacts')->where('id',$id)->where('idUser',$user->id)->delete();
	}
	
	public function getEmptyContact()
	{
		$user = Auth::user();
		DB::table('msg_contacts')->where('idUser',$user->id)->delete();;
	}
	
	public function postImportData()
	{
		$ret = array();
		$user = Auth::user();
		$destinationPath = "../storage/logs/";
		$fileName = date('YmdHis')."_".$user->name.".vcf";
		if (Input::hasFile('myfile'))
		{
			
			
			 	
  	   		 Input::file('myfile')->move($destinationPath, $fileName);
			 $file_temp = $destinationPath . $fileName;
			 $vCard = new vCard($file_temp,false,array( 'Collapse' => false));
			 if (count($vCard) == 0)
			 {
				throw new Exception('vCard test: empty vCard!');
			 }
			 elseif (count($vCard) == 1)
			 {
				MessageClass::OutputvCard($vCard);
			 }
			 else
			 {
		 		foreach ($vCard as $Index => $vCardPart)
				{
					MessageClass::OutputvCard($vCardPart);
				}
			 }
			 unlink($file_temp );
			 
		}
		echo json_encode($ret);
	}
	
	public function getImportData()
	{
		return view('message/contact-import-vcard');
	}
	
	public function postAddData(Request $request)
	{
		$user = Auth::user();
		$nama = $request->input('nama');
		$phone = $request->input('phone');
		$phone = MessageClass::formatNumber($phone);
		$strError = "";
		if($nama=="") $strError .= "<li>Nama tidak boleh kosong</li>";
		if($phone=="") $strError .= "<li>Phone tidak boleh kosong</li>";
		if($strError=="")
		{
			MessageClass::InsertContact($nama,$phone,$user->id);
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
		return view('message/contact-tambah');
	}
	
	public function getEditData($id)
	{
		$user = Auth::user();
		$contact = DB::table('msg_contacts')->where('id',$id)->where('idUser',$user->id)->first();
		return view('message/contact-edit')->with('contact',$contact);
	}
	
	public function postEditData(Request $request)
	{
		$user = Auth::user();
		$nama = $request->input('nama');
		$id = $request->input('id');
		$phone = $request->input('phone');
		$phone = MessageClass::formatNumber($phone);
		$strError = "";
		if($id=="") $strError .= "<li>Missing ID</li>";
		if($nama=="") $strError .= "<li>Nama tidak boleh kosong</li>";
		if($phone=="") $strError .= "<li>Phone tidak boleh kosong</li>";
		if($strError=="")
		{
			DB::table('msg_contacts')->where('id',$id)->where('idUser',$user->id)->update(['nama'=>$nama,'phone'=>$phone]);
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
	
	
	
}
?>