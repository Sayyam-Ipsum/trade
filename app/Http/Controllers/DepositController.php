<?php

namespace App\Http\Controllers;

use App\Interfaces\DepositInterface;
use App\Interfaces\PaymentMethodInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DepositController extends Controller
{
    protected DepositInterface $depositInterface;
    protected PaymentMethodInterface $paymentMethodInterface;

    public function __construct(DepositInterface $depositInterface, PaymentMethodInterface $paymentMethodInterface)
    {
        $this->depositInterface = $depositInterface;
        $this->paymentMethodInterface = $paymentMethodInterface;
    }

    public function index(Request $request)
    {
        if ($request->post()) {
            $validate = Validator::make($request->all(), [
                "user_id" => "required",
                "amount" => "required",
                "payment_method" => "required",
                "photo" => "required"
            ]);

            if ($validate->fails()) {
                return redirect()->back()->withErrors($validate);
            }

            $res = $this->depositInterface->storeDeposit($request);

            return redirect()->back()->with($res['type'], $res['message']);
        }

        if ($request->ajax()) {
            $id = auth()->id();
            $data = $this->depositInterface->depositListing($id);

            return DataTables::of($data)
                ->addColumn('date', function ($data) {
                    return showDate($data->created_at);
                })
                ->addColumn('amount', function ($data) {
                    return '$'.$data->amount;
                })
                ->addColumn('status', function ($data) {
                    return statusBadge($data->status);
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        $payment_methods = $this->paymentMethodInterface->paymentMethodListing(true);

        return view('site.trade.deposit', compact(['payment_methods']));
    }

    public function getDeposits(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->depositInterface->listing($request);

            return DataTables::of($data)
                ->addColumn('user', function ($data) {
                    return $data->user;
                })
                ->addColumn('bank', function ($data) {
                    return $data->bank;
                })
                ->addColumn('amount', function ($data) {
                    return '$'.$data->amount;
                })
                ->addColumn('photo', function ($data) {
                    $html = "N/A";
                    if (!empty($data->photo)) {
                        $html = '<a href="'.asset(''.$data->photo.'').'" target="_blank" class="ml-2">click here</a>';
                    }

                    return $html;
                })
                ->addColumn('status', function ($data) {
                    return statusDropdown("deposit", $data->status, $data->id);
                })
                ->rawColumns(['status', 'photo'])
                ->make(true);
        }

        $start_date = date('Y-m-d',strtotime('-1 year'));
        $end_date = date('Y-m-d', strtotime('+1 year'));

        return view('admin.deposits.listing', compact(['start_date', 'end_date']));
    }

    public function changeDepositStatus(Request $request)
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

        $res = $this->depositInterface->updateStatus($request);

        return response()->json($res);
    }
}
