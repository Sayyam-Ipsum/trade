<?php

namespace App\Http\Controllers;

use App\Interfaces\DepositInterface;
use App\Interfaces\PaymentMethodInterface;
use App\Models\User;
use App\Interfaces\TradeInterface;
use App\Models\Referral;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SiteController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->intended('/dashboard');
        }

        return view('site.pages.home');
    }

    public function dashboard()
    {
        $data['count_deposits'] = auth()->user()->countDeposit();
        $data['count_withdrawals'] = auth()->user()->countWithdraw();
        $data['count_trades'] = auth()->user()->countTrades();
        $data['count_referrals'] = auth()->user()->countReferral();

        return view('site.trade.dashboard', compact(['data']));
    }

    public function market()
    {
        return view('site.trade.market');
    }

    public function account()
    {
        return view('site.trade.account');
    }

    public function referral()
    {
        $referrals = Referral::where("referred_by", auth()->user()->id)->count();
        $referralEarning = User::find(auth()->user()->id)->referral_earning;

        return view('site.trade.referral', compact(['referrals', 'referralEarning']));
    }

    public function transactions()
    {
        return view('site.trade.transactions');
    }

    public function withdrawalAccount()
    {
        return view('site.trade.withdrawal-account');
    }

    public function getAccountBalance(Request $request)
    {
        $user = User::where('id', auth()->user()->id)
            ->select(
                "account_balance",
            )
            ->first();

        return response()->json(['success' => true, 'data' => $user]);
    }

    public function plans()
    {
        return view("site.trade.plans");
    }
}
