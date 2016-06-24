<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Input;
use Redirect;
use Mail;
use DB;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
	
    protected $redirectTo = '/admin';
	protected $loginPath = '/auth/login';
	protected $redirectAfterLogout = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }
	
	public function postLogin(Request $request)
	{
		if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password'), 'active' => 1])) {
            // Authentication passed...
            return redirect()->intended('/admin');
        }
		return redirect()->intended('/auth/login');
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
    			return Redirect::to('admin');
			}
		}
	}
	
	public function getRegister()
	{
		return view('auth.register')->with('success',0);
	}
	
	public function postRegister(Request $request)
	{
		$rules = [
			'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ];
		
		
		$validator = Validator::make($request->all(), $rules);
		
		if($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator);
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
		Mail::send('auth.emails.verify', $data, function ($message) use ($email,$name) {
    		$message->to($email,$name);
			$message->subject('Verify your email address');
		});
		
		
        return view('auth.register')->with('success',1);
	}
	
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
