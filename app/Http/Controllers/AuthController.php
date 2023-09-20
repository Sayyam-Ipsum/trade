<?php

namespace App\Http\Controllers;

use App\Interfaces\RoleInterface;
use App\Models\Referral;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected RoleInterface $roleInterface;

    public function __construct(RoleInterface $roleInterface)
    {
        $this->roleInterface = $roleInterface;
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')
                ->stateless()
                ->user();

            $finduser = User::where('google_id', $user->id)
                ->first();

            if ($finduser) {
                Auth::login($finduser);

                return redirect()->intended('/');
            } else {
                $lastId = User::get()->last() ? User::get()->last()->id : 0;
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_id' => $this->roleInterface->getCustomerRoleID(),
                    'google_id' => $user->id,
                    'uuid' => 1000 . $lastId + 1,
                    'password' => encrypt('btcridepasswd')
                ]);

                Auth::login($newUser);

                return redirect()->intended('/');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        if ($request->post()) {
            $credentials = $request->validate([
                'identifier' => ['required'],
                'password' => ['required'],
            ]);

            if (Auth::attempt(['email' => $request->identifier, 'password' => $request->password, 'is_admin' => 1])
                || Auth::attempt(
                    ['phone_number' => $request->identifier, 'password' => $request->password, 'is_admin' => 1]
                )) {
                $request->session()
                    ->regenerate();

                return redirect(url('/admin'));
            } elseif (Auth::attempt(['email' => $request->identifier, 'password' => $request->password])
                || Auth::attempt(['phone_number' => $request->identifier, 'password' => $request->password])) {
                $request->session()
                    ->regenerate();

                return redirect(url('/'));
            }

            return back()
                ->withErrors([
                    'email' => 'The provided credentials does not match our records.',
                ])
                ->onlyInput('email');
        }

        return view('site.pages.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()
            ->invalidate();

        $request->session()
            ->regenerateToken();

        return redirect('login');
    }

    public function register(Request $request)
    {
        if ($request->post()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required | max:50',
                'email' => 'required | email | max:200 | unique:users',
                'password' => 'required | min:8',
                'phone_number' => 'required | digits:11 | unique:users'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            DB::beginTransaction();
            try {
                $lastId = User::get()->last() ? User::get()->last()->id : 0;

                $newUser = new User();
                $newUser->email = preg_replace('/\s+/', '', strtolower($request->email));
                $newUser->password = password_hash($request->password, PASSWORD_DEFAULT);
                $newUser->name = $request->name;
                $newUser->role_id = $this->roleInterface->getCustomerRoleID();
                $newUser->uuid = 1000 . $lastId + 1;
                $newUser->phone_number = $request->phone_number;
                $newUser->save();
                $newUser->assignRole('Customer');

                if ($request->refCode) {
                    # adding record in referral table
                    Referral::create(['referred_by' => base64_decode($request->refCode), 'referral' => $newUser->id]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                log::debug($e->getMessage());
                return redirect()->back()->with("error", "Please contact to Administrator");
            }

            if ($newUser) {
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    $request->session()
                        ->regenerate();

                    return redirect(url('/'));
                }
            }
        }

        $refCode = $request->get('refcode');

        return view('site.pages.register', compact('refCode'));
    }


    public function forgotPassword(Request $request){

        if($request->all()) {
            $validation = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation->errors());
            }

            $user = User::where('email', $request->post('email')) -> first();

            if($user) {
                $token = Str::random(64);
                $password_reset_token_expiry_datetime = date("Y-m-d G:i:s", strtotime('+180 minutes'));

                $user -> password_reset_token = $token;
                $user -> password_reset_token_expiry_datetime = $password_reset_token_expiry_datetime;
                $user -> save();

                //return view('mails.password-forgot', compact(['token', 'user']));
                $is_mail_send = send_email($user -> email, "Reset password",  ['user' => $user] , 'password-forgot');
                if(!$is_mail_send){
                    return redirect() -> back() -> with('error', 'Email was not sent, please contact to administrator.');
                }
                return redirect( 'forgot-password') -> with('success', 'If it is registered email, we sent you an email which includes instructions to reset your password.');

            }
            return redirect('forgot-password') -> with('success', 'If it is registered email, We sent you an email which includes instructions to reset your password.');
        }
        return view('site.pages.forgot-password');

    }

    public function resetPassword($token) {

        if(!$token) {
            return redirect( route('/') ) -> with('error', 'No token found.');
        }

        $user = DB::table('users')->where('password_reset_token', $token)->first();

        //if token not found in database
        if(!$user) {
            return redirect( route('forgot-password') ) -> with('error', 'Password reset request was invalid or it has been expired.');
        }

        // check token expiry if greater than 1 hour
        if($user){
            $date1 = new DateTime(date('Y-m-d h:i:s'));
            $date2 = new DateTime($user -> password_reset_token_expiry_datetime);
            $interval = $date1->diff($date2);
            $hour = $interval->format('%h');
            if($hour ==  0  ){
                return redirect( route('forgot-password') ) -> with('error', 'Password reset request was invalid or it has been expired.');
            }
        }

        return view('site.pages.reset-password')->with(compact(['user']));
    }

    public function doResetPassword(Request $request) {

        $validation = Validator::make($request->all(), [
            'password' => 'min:8|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:8',
            'token'   => 'required'
        ]);
        if ($validation->fails()) {
            $res['status'] = false;
            $res['message'] = implode('<br>', $validation->errors()->all());
//            return response()->json($res);
             return redirect()->back()->with('error', $res['message']);
        }
        $token = ($request -> post('token'));

        $user = User::where('password_reset_token', $token)->first();
        // check token is valid or not
        if($user){
            // Update user password with the new one
            $user -> password = password_hash($request -> post('password'), PASSWORD_DEFAULT);

            // update token after setting new password
            $user ->password_reset_token = Str::random(64);
            $user -> save();
            $res['message'] = 'Password has been reset successfully';
            return redirect('login')->with('success', $res['message']);
        }
        $res['message'] = 'Invalid token';

        return redirect('login')->with('error', $res['message']);
    }

}
