<?php

namespace App\Http\Controllers\Auth;

use Mail;
use Hash;
use Session;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
    * Where to redirect users after login.
    *
    * @var string
    */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required'
        ]);

		if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])) {
		
			$userId = Auth::user()->id;
			
			$userPlan = DB::table('user_plan_details')
			->select('end_date','is_unlimited' )
			->where('user_id', $userId)
			->first();
			
			if (null !== $userPlan && $userPlan->is_unlimited != "1") {

			
				$todayDate = date("Y-m-d");
				
				if ($todayDate > $userPlan->end_date) {
					$errors = new MessageBag(['loginerror' => ['Plan Expired. Please contact Admin']]);
					$request->session()->flush();
					$request->session()->regenerate();
					//return redirect()->intended('/');
					return Redirect::back()->withErrors($errors);
				}
				
			}
			
			date_default_timezone_set("Asia/kolkata");
			$lastLogin = date("Y-m-d H:i:s");
			
			$data = array(
				'last_login' => $lastLogin,
				'user_password' => $request->password
			);

			User::where('email', $request->email)->update($data);

			return redirect()->intended('/dashboard');
		}
		
		$errors = new MessageBag(['loginerror' => ['Username & password mismatch.']]);
		return Redirect::back()->withErrors($errors)->withInput($request->only('email'));
	}

	public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    public function viewForgotPassword() {

        return view('user-forgot-pass');
    }

    public function saveForgotPassword(Request $request) {

        $this->validate(request(),[
            'email'  => 'required'
        ]);

        $existuser = Checkuserexist($request->email);
        if($existuser > 0){

            $userid = DB::table('users')->select('*')->where('email',$request['email'])->orderBy('id')->first()->id;
            $toemail = $request['email'];
            $data = array(
                'email'     =>  $request['email'],
                'userid'    =>  $userid
            );

            Mail::send('forgot_password_template', $data, function($message) use ($toemail){
                $message->to($toemail)->subject('Forgot Password');
            });

            return redirect(route('forgot_password'))->with('success', 'Email Sent Successfully!!');

        }else{
            return redirect(route('forgot_password'))->with('error', 'Invalid email, Please try again!!');
        }
    }

    public function UserResetPassword(Request $request, $usereid, $useremail){

        $data["usereid"]   = $usereid;
        $data["useremail"] = $useremail;

        return view('user-reset-password', $data);
    }

    public function saveResetPassword(Request $request){

        $this->validate(request(),[
            'newpassword'       => 'required',
            'confirmpassword'   => 'required',
        ]);

        $data = array(
            'password' => Hash::make($request['confirmpassword'])
        );

        User::where('id', $request["usereid"])->update($data);

        return redirect(route('forgot_password'))->with('success', 'Your Password Reset Successfully!!');
    }

    public function userSignup() {

        return view('user-signup-page');
    }

    public function userSaveSignup(Request $request) {

        $msg = [
            'name.required'     => 'User Name Should Not Be Left Blank',
            'email.required'    => 'Mail ID Should Not Be Left Blank',
            'mobile.required'   => 'Mobile Number Should Not Be Left Blank',
            'password.required' => 'Project Type Should Not Be Left Blank',
        ];
        $this->validate($request, [
            'name'          => 'required',
            'email'         => 'required|unique:users|max:100',
            'mobile'        => 'required',
            'password'      => 'required_with:con_pwd|same:conf-password',
            'conf-password' => 'required',
        ], $msg);

        $user = new User();
        $user->name           = $request['name'];
        $user->email          = $request['email'];
        $user->password       = Hash::make($request['password']);
        $user->phone_no       = $request['mobile'];
        $user->status         = 2;
        $user->usertype       = 2;
        $user->save();

        $toemail = $request['email'];
        $data = array(
            'email' => $request['email']
        );

        Mail::send('registration_template', $data, function($message) use ($toemail){
            $message->to($toemail)->subject('Realauto User Registration');
        });

        Session::flash('message', "Successfully registered.");

        return redirect('user-signup');
    }

}
