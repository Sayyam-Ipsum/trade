<?php

namespace App\Repositories;

use App\Interfaces\PaymentMethodInterface;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentMethodRepository implements PaymentMethodInterface
{
    public function paymentMethodListing($active = false)
    {
        if ($active) {
            return PaymentMethod::where("status", "active")->select("id", "bank")->get();
        }

        return PaymentMethod::all();
    }

    public function storePaymentMethod(Request $request): array
    {
        $res["status"] = false;
        try {
            DB::beginTransaction();
            $payment_method = isset($request->id) ? PaymentMethod::find($request->id) : new PaymentMethod();
//            $payment_method->mobile_no = $request->mobile_no;
            $payment_method->account_title = $request->account_title;
            $payment_method->account_no = $request->account_no;
            $payment_method->bank = $request->bank;
            $payment_method->save();
            DB::commit();
            $res["status"] = true;
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return $res;
    }

    public function updateStatus(Request $request)
    {
        $res["type"] = "error";
        try {
            DB::beginTransaction();
            $payment = PaymentMethod::find($request->id);
            $payment->status = $request->status;
            $payment->save();
            DB::commit();
            $res["type"] = "success";
            $res["message"] = "Status Updated Successfully";
        } catch (\Exception $e) {
            DB::rollBack();
            $res["message"] = "Internal Server Error";
        }

        return $res;
    }

    public function getPaymentMethodDetail($id)
    {
        return PaymentMethod::find($id);
    }
}
