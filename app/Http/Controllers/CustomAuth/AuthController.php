<?php
namespace App\Http\Controllers\CustomAuth;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Http\Request;
use Validator;
use App\User;
use Mail;
use Redirect;

class AuthController extends Controller
{
	public function __construct()
	{
    	
	}
	
	public function getLogin()
	{
		if(!Auth::guest())
		{
			return redirect('/auth/dashboard');	
		}
		return view('customauth.login');
	}
	
	public function postLogin(Request $request)
	{
			$strError = "";
			$email = $request->input('email');
			$password = $request->input('password');
			$remember = $request->input('remember');
			$remember = ($remember == "true" ? true : false);
			
			
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
			}
		}
		return redirect("/auth/dashboard");
	}
	
	public function getEmail()
	{
		return view('customauth.password');
	}
	
	public function postEmail(Request $request)
	{
		$strError = "";
		$email =  $request->input('email');
		if($email=="") $strError .= "<li>Email tidak boleh kosong</li>";
		if($strError=="")
		{
			$check = DB::table('users')->where('email',$email)->first();
			if(count($check)==0) $strError = "<li>Email tidak ditemukan</li>";	
		}
		if($strError=="")
		{
			$token = str_random(30);
			DB::table('password_resets')->insert(['email'=>$email,'token'=>$token,'created_at'=>date('Y-m-d H:i:s')]);
			$data = array('email'=>$email, 'token' => $token);
			Mail::send('customauth.emails.password', $data, function ($message) use ($email) {
    			$message->to($email);
				$message->subject('Reset Password');
			});
			exit('<div class="alert alert-success"><ul>Link untuk mereset password telah dikirimkan ke email anda</ul></div>');
		}
		
		exit('<div class="alert alert-danger"><ul>'.$strError.'</ul></div>');
	}
	
	public function getReset($token)
	{
		return view('customauth.reset')->with('token',$token);
	}
	
	public function postReset(Request $request)
	{
		$name =  $request->input('name');
		$email =  $request->input('email');
		$password =  $request->input('password');
		$token =  $request->input('token');
		$strError = "";
		
		$rules = [
            'email' => 'required|email|max:255',
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
		
		$check = DB::table('password_resets')->where('email',$email)->where('token',$token)->first();
		if(count($check)==0) $strError = "<li>Invalid email or token</li>";
		if($strError=="")
		{
			//DB::table('password_resets')->where('email',$email)->delete();
			DB::table('users')->where('email',$email)->update(['password'=>bcrypt($password),'active'=>1,'updated_at'=>date('Y-m-d H:i:s')]);
			if (Auth::attempt(['email' => $email, 'password' => $password, 'active' => 1])) {
   				$rs = DB::table('users')->where('email',$email)->first();
				Auth::loginUsingId($rs->id);
				return "success";
			}
		}
		exit('<div class="alert alert-danger"><ul>'.$strError.'</ul></div>');
	}
	
	public function postLogout()
	{
		Auth::logout();
		return redirect("/");
	}
	
}
?>