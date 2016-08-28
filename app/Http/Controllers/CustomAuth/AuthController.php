<?php
namespace App\Http\Controllers\CustomAuth;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Http\Request;
use Validator;
use App\User;
use Mail;

class AuthController extends Controller
{
	public function __construct()
	{
    	
	}
	
	public function getLogin()
	{
		return view('customauth.login');
	}
	
	public function postLogin(Request $request)
	{
			$strError = "";
			$email = $request->input('email');
			$password = $request->input('password');
			$remember = $request->input('remember');
			
			
			if($strError=="")
			{
				if (Auth::attempt(['email' => $email, 'password' => $password, 'active' => 1], $remember)) {
    				
					print('success');
				}
				else
				{
					$strError .= "<li>Login failed</li>";
				}
				
			}
			
			
			if($strError!="") print('<div class="alert alert-danger"><ul>'.$strError.'</ul></div>');
	}
	
	public function getRegister()
	{
		return view('customauth.register');
	}
	
	public function postRegister(Request $request)
	{
		$strError = "";
		
		$rules = [
			'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ];
		
		$validator = Validator::make($request->all(), $rules);
		
		if($validator->fails())
        {
			
			foreach ($validator->errors()->all() as $error)
			{
				
				$strError .= '<li>'.$error.'</li>';
			}
            exit('<div class="alert alert-danger"><ul>'.$strError.'</ul></div>');
        }
		
		$confirmation_code = str_random(30);
		
		$name =  $request->input('name');
		$email =  $request->input('email');
		$password =  $request->input('password');
		$user = new User;
		$user->name = $name;
		$user->email = $email;
		$user->password = bcrypt($password);
		$user->confirmation_code = $confirmation_code;
		$user->save();
		
		$data = array('email'=>$email, 'confirmation_code' => $confirmation_code);
		Mail::send('customauth.emails.verify', $data, function ($message) use ($email,$name) {
    		$message->to($email,$name);
			$message->subject('Verify your email address');
		});
		
		exit('<div class="alert alert-success"><ul>Registrasi berhasil silakan check email anda</ul></div>');
	}
	
	public function getVerify($email, $confirmation_code)
	{
		
		$result = DB::table('users')->select('id','email','password')
			   ->where('email', $email)
			   ->where('active', 0)
			   ->where('confirmation_code',$confirmation_code)
			   ->get();
		
		if(count($result))
		{
			foreach($result as $rs)
			{
				DB::table('users')->where('id',$rs->id)->update(['active' => 1]);	
				Auth::loginUsingId($rs->id);
    			return Redirect::to('/user/dashboard');
			}
		}
	}
	
	public function getEmail()
	{
		return view('customauth.password');
	}
}
?>