<?php

namespace App\Http\Controllers;

use App\Interfaces\DepositInterface;
use App\Interfaces\PaymentMethodInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SiteController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->intended('/market');
        }

        return view('site.pages.home');
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
        return view('site.trade.referral');
    }

    public function tradeListing()
    {
        return view('site.trade.listing');
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
}
