<?php

namespace App\Repositories;

use App\Interfaces\WithdrawalInterface;
use App\Models\User;
use App\Models\UserAccount;
use App\Models\Withdraw;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalRepository implements WithdrawalInterface
{
    public function listing(Request $request)
    {
        $data = Withdrawal::join("users", "withdrawals.user_id", "users.id")
            ->join("user_withdrawal_accounts", "withdrawals.user_account_id", "user_withdrawal_accounts.id");

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $data = $data->whereBetween(DB::raw('DATE(withdrawals.created_at)'), [$request->start_date, $request->end_date]);
        }

        $data = $data->select(
            "users.name as user",
            "user_withdrawal_accounts.bank",
            "user_withdrawal_accounts.account_title",
            "user_withdrawal_accounts.account_no",
            "withdrawals.amount",
            "withdrawals.status",
            "withdrawals.id"
        )
            ->orderBy("withdrawals.id", "desc")
            ->get();

        return $data;
    }

    public function updateStatus(Request $request)
    {
        $res["type"] = "error";
        try {
            DB::beginTransaction();
            $withdrawal = Withdrawal::find($request->id);
            $withdrawal->status = $request->status;
            $withdrawal->save();
            DB::commit();
            if ($withdrawal->status == "approved") {
                $this->withdrawFromUserAccount($withdrawal);
            }
            $res["type"] = "success";
            $res["message"] = "Status Updated Successfully";
        } catch (\Exception $e) {
            DB::rollBack();
            $res["message"] = "Internal Server Error";
        }

        return $res;
    }

    public function withdrawFromUserAccount($data)
    {
        try {
            DB::beginTransaction();
            $user = User::find($data->user_id);
            $user->account_balance -= $data->amount;
            $user->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function storeWithdrawalAccount(Request $request)
    {
        $res["type"] = "error";
        try {
            $check = UserAccount::where("user_id", $request->user_id)
                ->where("bank", $request->bank)
                ->where("account_no", $request->account_number)
                ->first();
            if ($check) {
                $res["type"] = "warning";
                $res["message"] = "Account Already exists! Please create new one";

                return $res;
            }
            DB::beginTransaction();
            $account = new UserAccount();
            $account->user_id = $request->user_id;
            $account->bank = $request->bank;
            $account->account_title = $request->account_title;
            $account->account_no = $request->account_no;
            $account->mobile_no = $request->mobile_no;
            $account->save();
            DB::commit();
            $res["type"] = "success";
            $res["message"] = "Account Saved Successfully";
        } catch (\Exception $e) {
            DB::rollBack();
            $res["message"] = "Please contact to Administrator";
        }

        return $res;
    }

    public function storeWithdrawal(Request $request)
    {
        $res["type"] = "error";
        try {
            DB::beginTransaction();
            $withdraw = new Withdrawal();
            $withdraw->user_id = $request->user_id;
            $withdraw->amount = $request->amount;
            $withdraw->user_account_id = $request->account;
            $withdraw->status = "pending";
            $withdraw->save();
            DB::commit();
            $res["type"] = "success";
            $res["message"] = "Withdraw Submitted, Please wait.....";
        } catch (\Exception $e) {
            DB::rollBack();
            $res["message"] = "Please contact to Administrator";
        }

        return $res;
    }

    public function withdrawalListing($userID = null)
    {
        $data = Withdrawal::with("user");

        if (!empty($user_id)) {
            $data = $data->where("user_id", $userID);
        }

        $data = $data->orderBy("id", "desc")
            ->get();

        return $data;
    }

    public function withdrawalAccountListing($userID)
    {
        return UserAccount::where("user_id", $userID)
            ->orderBy("id", "desc")
            ->get();
    }
}
