<?php

namespace App\Http\Controllers;

use App\Interfaces\SettingInterface;
use App\Interfaces\WithdrawalInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class WithdrawalController extends Controller
{
    protected WithdrawalInterface $withdrawalInterface;
    public SettingInterface $settingInterface;

    public function __construct(WithdrawalInterface $withdrawalInterface, SettingInterface $settingInterface)
    {
        $this->withdrawalInterface = $withdrawalInterface;
        $this->settingInterface = $settingInterface;
    }

    public function index(Request $request)
    {
        if ($request->post()) {
            $validate = Validator::make($request->all(), [
                "user_id" => "required",
                "amount" => "required",
                "account" => "required"
            ]);

            if ($validate->fails()) {
                return redirect()->back()->withErrors($validate);
            }

            if ($request->amount > auth()->user()->account_balance) {
                return back()->with("warning", "Sorry, Your account balance is less than the withdrawal amount");
            }

            $setting = $this->settingInterface->show();
            if ($request->amount < $setting->withdraw_limit) {
                return back()->with("warning", "Sorry, Minimum Withdraw limit is ".$setting->withdraw_limit."$");
            }
            $res = $this->withdrawalInterface->storeWithdrawal($request);

            return redirect()->back()->with($res['type'], $res['message']);
        }

        if ($request->ajax()) {
            $id = auth()->id();
            $data = $this->withdrawalInterface->withdrawalListing($id);

            return DataTables::of($data)
                ->addColumn('date', function ($data) {
                    return showDate($data->created_at);
                })
                ->addColumn('amount', function ($data) {
                    return $data->amount;
                })
                ->addColumn('status', function ($data) {
                    return statusBadge($data->status);
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        $accounts = $this->withdrawalInterface->withdrawalAccountListing(auth()->id());

        return view('site.trade.withdrawal', compact(['accounts']));
    }

    public function getWithdrawals(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->withdrawalInterface->listing($request);

            return DataTables::of($data)
                ->addColumn('user', function ($data) {
                    return $data->user;
                })
                ->addColumn('bank', function ($data) {
                    return $data->bank;
                })
                ->addColumn('account_title', function ($data) {
                    return $data->account_title;
                })
                ->addColumn('account_no', function ($data) {
                    return $data->account_no;
                })
                ->addColumn('amount', function ($data) {
                    return $data->amount;
                })
                ->addColumn('status', function ($data) {
                    return statusDropdown("withdrawal", $data->status, $data->id);
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        $start_date = date('Y-m-d',strtotime('-1 year'));
        $end_date = date('Y-m-d', strtotime('+1 year'));

        return view('admin.withdrawals.listing', compact(['start_date', 'end_date']));
    }

    public function changeWithdrawalStatus(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id" => "required",
            "status" => "required"
        ]);

        if ($validate->fails()) {
            $res["type"] = "error";
            $res["message"] = "Validation Error";

            return response()->json($res);
        }

        $res = $this->withdrawalInterface->updateStatus($request);

        return response()->json($res);
    }

    public function storeWithdrawalAccount(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "user_id" => "required",
            "bank" => "required",
            "account_title" => "required",
            "account_no" => "required",
            "mobile_no" => "required"
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

        $res = $this->withdrawalInterface->storeWithdrawalAccount($request);

        return redirect()->back()->with($res['type'], $res['message']);
    }
}
