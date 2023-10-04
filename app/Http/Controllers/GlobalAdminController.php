<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Models\Trade;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class GlobalAdminController extends Controller
{
    public function index()
    {
        return redirect()->route('global-admin.dashboard');
    }

    public function dashboard()
    {
        return view('admin.global.dashboard');
    }

    public function tradeListing(Request $request)
    {
        if ($request->ajax()) {
            $data = Trade::where("user_id", $request->user_id)
                ->orderBy("trades.id", "desc");

            return DataTables::of($data)
                ->addColumn('date', function ($data) {
                    return showDateTime($data->created_at);
                })
                ->addColumn('amount', function ($data) {
                    return '$'.$data->amount;
                })
                ->addColumn('profitable_amount', function ($data) {
                    return '$'.$data->profitable_amount;
                })
                ->addColumn('type', function ($data) {
                    return statusBadge($data->type) . ' ' . statusBadge($data->status);
                })
                ->addColumn("result", function ($data) {
                    return statusBadge($data->result);
                })
                ->addColumn('actions', function ($data) {

                    return '<button class="btn btn-xs btn-outline-success btn-result mr-1" data-id="'.$data->id.'" data-result="profit">Profit</button>
                            <button class="btn btn-xs btn-outline-danger btn-result mr-1" data-id="'.$data->id.'" data-result="loss">Loss</button>';
                })
                ->rawColumns(['type', 'actions', 'result'])
                ->make(true);
        }

        $users = User::select("id", "name")->get();

        return view('admin.global.trades', compact(['users']));
    }

    public function balanceModal($id)
    {
        $balance = User::find($id)->account_balance;
        $res["title"] = "Account Balance";
        $res["html"] = view('admin.global.balance-modal', compact(['id', 'balance']))->render();

        return response()->json($res);
    }

    public function updateBalance(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "user_id" => "required",
            "amount" => "required"
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

//        $paymentMethodID = PaymentMethod::where("status", "active")->first()->id;
//        $deposit = new Deposit();
//        $deposit->user_id = $request->user_id;
//        $deposit->amount = $request->amount;
//        $deposit->payment_method_id = $paymentMethodID;
//        $deposit->status = 'approved';
//        $deposit->photo = '';
//        $deposit->save();
        $res["type"] = "error";
        try {
            DB::beginTransaction();
            $user = User::find($request->user_id);
            $user->account_balance += $request->amount;
            $user->save();
            DB::commit();
            $res["type"] = "success";
            $res["message"] = "Balance Updated";
        } catch (Exception $e) {
            DB::rollBack();
            $res["message"] = "Internal Server Error";
        }

        return redirect(url('gadmin/trades'))->with($res["type"], $res["message"]);
    }

    public function updateTrade(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id" => "required",
            "result" => "required"
        ]);

        if ($validate->fails()) {
            $res["status"] = false;
            $res["message"] = "Validation Error";

            return response()->json($res);
        }

        $res["status"] = false;
        try {
            DB::beginTransaction();
            $trade = Trade::find($request->id);
            $trade->result = $request->result;
            $trade->status = "completed";
            $trade->save();

            $user = User::find($trade->user_id);
            if ($request->result == "profit") {
                $user->account_balance += $trade->profitable_amount;
            }

            if ($request->result == "loss") {
                $user->account_balance -= $trade->amount;
            }

            $user->save();
            DB::commit();
            $res["status"] = true;
            $res["message"] = "Updated";
        } catch (Exception $e) {
            DB::rollBack();
            $res["message"] = "Internal Server Error";
        }

        return response()->json($res);
    }
}
