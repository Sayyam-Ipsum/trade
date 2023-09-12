<?php

namespace App\Repositories;

use App\Interfaces\TradeInterface;
use App\Models\Signal;
use App\Models\Trade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TradeRepository implements TradeInterface
{
    public function listing(Request $request)
    {
        $data = Trade::join("users", "trades.user_id", "users.id");

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $data = $data->whereBetween(DB::raw('DATE(trades.created_at)'), [$request->start_date, $request->end_date]);
        }

        $data = $data->select(
            "users.name as user",
            "trades.amount",
            "trades.profitable_amount",
            "trades.type",
            "trades.result",
            "trades.id",
            "trades.status"
        )
            ->orderBy("trades.id", "desc")
            ->get();

        return $data;
    }

    public function store(Request $request)
    {
        $res['status'] = false;
        try {
            // Todo: check for signal
            $currentDateTime = Carbon::now();
            $checkSignal = Signal::where("start_time", "<=", $currentDateTime)->where("end_time", ">=", $currentDateTime)->first();
            DB::beginTransaction();
            $trade = new Trade();
            if ($checkSignal) {
                $trade->signal_id = $checkSignal->id;
            }
            $trade->user_id = $request->user_id;
            $trade->type = $request->type;
            $trade->amount = $request->amount;
            $trade->profitable_amount = $request->profitable_amount;
            $trade->save();
            DB::commit();
            $res['status'] = true;
            $res['message'] = "Trade Submitted";
        } catch (\Exception $e) {
            DB::rollBack();
            $res['message'] = "Please contact to Administrator";
        }

        return $res;
    }

    public function result(Request $request)
    {
        $res['status'] = false;
        try {
            DB::beginTransaction();
            $trade = Trade::find($request->id);
            $this->updateAccountBalance($trade, $request->result);
            $trade->result = $request->result;
            $trade->status = "completed";
            $trade->save();
            DB::commit();
            $res['status'] = true;
            $res['message'] = "Result Saved";
        } catch (\Exception $e) {
            DB::rollBack();
            $res['message'] = "Please contact to  Administrator";
        }

        return $res;
    }

    public function updateAccountBalance($trade, $result) {
        $user = User::find($trade->user_id);
        if ($result == "profit") {
            $user->account_balance += $trade->profitable_amount;
        }

        if ($result == "loss") {
            $user->account_balance -= $trade->amount;
        }

        $user->save();
    }

    public function liveTrading()
    {
        $signals = Signal::whereDate("signals.created_at", Carbon::today())->get();
//        $signals = Signal::all();

        if (count($signals) < 1)    return [];

        foreach ($signals as $signal) {
            $trades = Trade::where("signal_id", $signal->id)
//                ->where("status", "in-progress")
                ->select(
                    "type",
                    DB::raw("COUNT(*) as trades_count"),
                    DB::raw("SUM(profitable_amount) as trades_sum")
                )
                ->groupBy("type")
                ->get();
            $signal->trades = $trades;
        }

        return $signals;
    }

    public function storeSignalResult(Request $request)
    {
        $res['status'] = false;
        try {
            $signal = Signal::find($request->signal_id);

            if (!$signal) {
                $res['message'] = "Signal not found";

                return response()->json($res);
            }

            $trades = $signal->trades;
            if (count($trades) > 0) {
                foreach ($trades as $trade) {
                    // $request->result = buy | sell
                    // $request->type = profit | loss
                    if ($trade->type == $request->result) {
                        $update['result'] = $request->type;
                        $this->updateAccountBalance($trade, $request->type);
                    } else {
                        $type = $request->type == "profit" ? "loss" : "profit";
                        $update['result'] = $type;
                        $this->updateAccountBalance($trade, $type);
                    }
                    $update['status'] = "completed";


                    Trade::where("id", $trade->id)
                        ->update($update);
                }
            }

            $signal->status = "completed";
            $signal->save();

            $res['status'] = true;
            $res['message'] = "Trade Completed";
        } catch (\Exception $e) {
            $res['message'] = "Please contact to Administrator";
        }

        return $res;
    }
}
