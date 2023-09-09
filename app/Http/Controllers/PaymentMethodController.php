<?php

namespace App\Http\Controllers;

use App\Interfaces\PaymentMethodInterface;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PaymentMethodController extends Controller
{
    protected PaymentMethodInterface $paymentMethodInterface;

    public function __construct(PaymentMethodInterface $paymentMethodInterface)
    {
        $this->paymentMethodInterface = $paymentMethodInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $paymentMethod = $this->paymentMethodInterface->paymentMethodListing();

            return DataTables::of($paymentMethod)
                ->addColumn('account_title', function ($paymentMethod) {
                    return $paymentMethod->account_title;
                })
                ->addColumn('account_no', function ($paymentMethod) {
                    return $paymentMethod->account_no;
                })
                ->addColumn('bank', function ($paymentMethod) {
                    return $paymentMethod->bank;
                })
//                ->addColumn('mobile_no', function ($paymentMethod) {
//                    return $paymentMethod->mobile_no;
//                })
                ->addColumn('status', function ($paymentMethod) {
                    return statusDropdown("payment_method", $paymentMethod->status, $paymentMethod->id);
                })
                ->addColumn('actions', function ($paymentMethod) {
                    return '<a href="javascript:void(0);" data-id="' . $paymentMethod->id . '"
                    class="btn btn-sm btn-edit btn-primary mr-1" ><i class="fas fa-edit mr-1"></i>Edit</a>';
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }

        return view('admin.payment-methods.listing');
    }

    public function modal($id = null){
        if($id){
            $res["title"]   = 'Edit Payment Method';
            $paymentMethod = PaymentMethod::find($id);
            $res["html"]    = view('admin.payment-methods.form', compact(['paymentMethod']))->render();
        }
        else{
            $res["title"]   = 'Add New Payment Method';
            $res["html"]    = view('admin.payment-methods.form')->render();
        }

        return response()->json($res);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'account_title' => 'required',
            'account_no' => 'required',
//            'mobile_no' => 'required',
            'bank' => 'required',
        ]);
        if (!$validator->fails()) {
            $res = $this->paymentMethodInterface->storePaymentMethod($request);
            if ($res) {
                $msg = $request->id ? 'Payment Method Updated' : 'Payment Method Added';
                return redirect('admin/payment-methods')->with('success', $msg);
            } else {
                return redirect('admin/payment-methods')->with('error', 'Something went wrong');
            }
        } else {
            return redirect(url('admin/payment-methods'))->withErrors($validator->errors());
        }
    }

    public function changePaymentStatusStatus(Request $request)
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

        $res = $this->paymentMethodInterface->updateStatus($request);

        return response()->json($res);
    }

    public function getPaymentMethodDetail($id)
    {
        return $this->paymentMethodInterface->getPaymentMethodDetail($id);
    }
}
