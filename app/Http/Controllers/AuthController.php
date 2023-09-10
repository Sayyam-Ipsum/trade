<?php

namespace App\Http\Controllers;

use App\Interfaces\RoleInterface;
use App\Models\Referral;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_id' => $this->roleInterface->getCustomerRoleID(),
                    'google_id' => $user->id,
                    'password' => encrypt('easytradepasswd')
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
                'email' => ['required'],
                'password' => ['required'],
            ]);

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_admin' => 1])) {
                $request->session()
                    ->regenerate();

                return redirect()->intended('admin');
            } elseif (Auth::attempt($credentials)) {
                $request->session()
                    ->regenerate();

                return redirect(url('/trade'));
            }

            return back()
                ->withErrors([
                    'email' => 'The provided credentials do not match our record.',
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
                'password' => 'required | min:8'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }

            DB::beginTransaction();
            try {
                $newUser = new User();
                $newUser->email = preg_replace('/\s+/', '', strtolower($request->email));
                $newUser->password = password_hash($request->password, PASSWORD_DEFAULT);
                $newUser->name = $request->name;
                $newUser->role_id = $this->roleInterface->getCustomerRoleID();
                $newUser->save();
                $newUser->assignRole('Customer');


                if ($request->refCode) {
                    $refAmount = Setting::first()->referral_amount;
                    $u = User::where("id", base64_decode($request->refCode))->first();
                    $u->account_balance +=  $refAmount;
                    $u->save();
                    # adding record in referral table
                    Referral::create(['referred_by' => base64_decode($request->refCode), 'referral' => $newUser->id]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

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

    public function forgot()
    {
        return view('site.pages.forgot-password');
    }

    public function reset()
    {
        return view('site.pages.reset-password');
    }
}
