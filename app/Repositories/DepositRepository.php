<?php

namespace App\Repositories;

use App\Interfaces\DepositInterface;
use App\Models\Deposit;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class DepositRepository implements DepositInterface
{
    public function listing(Request $request)
    {
        $data = Deposit::join("users", "deposits.user_id", "users.id")
            ->join("payment_methods", "deposits.payment_method_id", "payment_methods.id");

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $data = $data->whereBetween(DB::raw('DATE(deposits.created_at)'), [$request->start_date, $request->end_date]);
        }

        $data = $data->select(
            "users.name as user",
            "payment_methods.bank",
            "deposits.amount",
            "deposits.status",
            "deposits.id",
            "deposits.photo"
        )
            ->orderBy("deposits.id", "desc")
            ->get();

        return $data;
    }

    public function updateStatus(Request $request)
    {
        $res["type"] = "error";
        try {
            DB::beginTransaction();
            $deposit = Deposit::find($request->id);
            $deposit->status = $request->status;
            $deposit->save();
            if ($deposit->status == "approved") {
                $referral = Referral::where('referral', $deposit->user_id)
                    ->where('status', 0)
                    ->first();

                if ($referral) {
                    $setting = Setting::first();
                    $referralAmount = ($deposit->amount / 100) * $setting->referral_percentage;
                    $user = User::find($referral->referred_by);
                    $user->account_balance = $user->account_balance + $referralAmount;
                    $user->referral_earning = $user->referral_earning + $referralAmount;
                    $referral->status = 1;
                    $user->save();
                    $referral->save();
                }
                
                $this->rechargeUserAccount($deposit);
            }
            DB::commit();
            $res["type"] = "success";
            $res["message"] = "Status Updated Successfully";
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            $res["message"] = "Internal Server Error";
        }

        return $res;
    }

    public function rechargeUserAccount($data)
    {
        try {
            DB::beginTransaction();
            $user = User::find($data->user_id);
            $user->account_balance += $data->amount;
            $user->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
        }
    }

    public function storeDeposit(Request $request)
    {
        $res["type"] = "error";
        try {
            DB::beginTransaction();
            $deposit = new Deposit();
            $deposit->user_id = $request->user_id;
            $deposit->amount = $request->amount;
            $deposit->payment_method_id = $request->payment_method;
            $deposit->status = "pending";
            if (isset($request->photo)) {
                $photo = $request->file('photo');
                $name = time(
                    ) . '_customer_' . $request->user_id . '_' . $request->amount . '_' . $photo->getClientOriginalName(
                    );
                $photo->move(public_path('uploads/payment_receipts'), $name);
                $deposit->photo = '/uploads/payment_receipts/' . $name;
            }
            $deposit->save();
            DB::commit();
            $res["type"] = "success";
            $res["message"] = "Deposit Successful";
        } catch (\Exception $e) {
            DB::rollBack();
            $res["message"] = "Please contact to Administrator";
        }

        return $res;
    }

    public function depositListing($userID = null)
    {
        $data = Deposit::with("user", "payment_method");

        if (isset($userID)) {
            $data = $data->where("user_id", $userID);
        }

        $data = $data->orderBy("id", "desc")
            ->get();

        return $data;
    }
}
