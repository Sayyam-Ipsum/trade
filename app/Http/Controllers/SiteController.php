<?php

namespace App\Http\Controllers;

use App\Interfaces\DepositInterface;
use App\Interfaces\PaymentMethodInterface;
use App\Interfaces\TradeInterface;
use App\Models\Referral;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SiteController extends Controller
{
    protected TradeInterface $tradeInterface;

    public function __construct(TradeInterface $tradeInterface)
    {
        $this->tradeInterface = $tradeInterface;
    }

    public function index()
    {
        if (Auth::check()) {
            return redirect()->intended('/market');
        }

        return view('site.pages.home');
    }

    public function market()
    {
        $trades = $this->tradeInterface->getTrades("today");

        return view('site.trade.market', compact(['trades']));
    }

    public function account()
    {
        return view('site.trade.account');
    }

    public function referral()
    {
        $referrals = Referral::where("referred_by", auth()->user()->id)->count();
        $referrer_amount = Setting::select("referral_amount")->pluck("referral_amount")->first();

        return view('site.trade.referral', compact(['referrals', 'referrer_amount']));
    }

    public function transactions()
    {
        return view('site.trade.transactions');
    }
}
