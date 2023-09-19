<?php

namespace App\Repositories;

use App\Interfaces\SignalInterface;
use App\Interfaces\TradeInterface;
use App\Models\Signal;
use App\Models\Trade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TradeRepository implements TradeInterface
{
    protected SignalInterface $signalInterface;
    public function __construct(SignalInterface $signalInterface)
    {
        $this->signalInterface = $signalInterface;
    }

    public function listing(Request $request, $signalID = null)
    {
        $data = Trade::join("users", "trades.user_id", "users.id");

        if (isset($signalID)) {
            $data = $data->where("signal_id", $signalID);
        }

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

    public function getTrades($filter = null)
    {
        $data = Trade::where("user_id", auth()->user()->id)->orderBy("created_at", "desc");

        if (isset($filter)) {
            switch ($filter) {
                case "today":
                    $data = $data->whereDate("created_at", Carbon::today());
                    $data = $data->take(7);
                    break;
                default:
                    break;
            }
        }

        return $data->get();
    }

    public function store(Request $request)
    {
        $res['status'] = false;
        try {
            $currentDateTime = Carbon::now();
            $checkSignal = Signal::where("start_time", "<=", $currentDateTime)->where("end_time", ">=", $currentDateTime)->first();
            DB::beginTransaction();
            $trade = new Trade();
            if ($checkSignal) {
                $checkDoubleTrade = Trade::where("signal_id", $checkSignal->id)->where("user_id", $request->user_id)->first();
                if ($checkDoubleTrade) {
                    $res['message'] = "You can't make trade at this time.";

                    return $res;
                }
                $trade->signal_id = $checkSignal->id;
            }
            $trade->user_id = $request->user_id;
            $trade->type = $request->type;
            $trade->amount = $request->amount;
            $trade->profitable_amount = $request->profitable_amount;
            $trade->save();

            $user = User::find($request->user_id);
            $user->account_balance -= $request->amount;
            $user->save();

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
        $currentTime = date("Y-m-d H:i:s");
        $duration='-35 minutes';
        $endTime = date('Y-m-d H:i:s', strtotime($duration, strtotime($currentTime)));

        $signals = Signal::where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $endTime)
            ->selectRaw("DATE_FORMAT(start_time,'%r') as start_time, DATE_FORMAT(end_time,'%r') as end_time, result, id, status")
            ->get();

        if (count($signals) < 1)    return [];

        foreach ($signals as $signal) {
            $signal->trades = $this->signalInterface->details($signal->id);
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

            $update = [];
            $i = 0;
            $trades = $signal->trades;
            if (count($trades) > 0) {
                foreach ($trades as $trade) {
                    $update[$i]['id'] = $trade->id;
                    $update[$i]['user_id'] = $trade->user_id;
                    $update[$i]['amount'] = $trade->amount;
                    $update[$i]['profitable_amount'] = $trade->profitable_amount;
                    // $request->result = buy | sell
                    // $request->type = profit | loss
                    if ($trade->type == $request->result) {
                        $update[$i]['result'] = $request->type;
                        $this->updateAccountBalance($trade, $request->type);
                    } else {
                        $type = $request->type == "profit" ? "loss" : "profit";
                        $update[$i]['result'] = $type;
                        $this->updateAccountBalance($trade, $type);
                    }
                    $update[$i]['status'] = "completed";
                    $i++;
                }
            }

            Trade::upsert($update, ['id'], ['user_id', 'result', 'status', 'amount', 'profitable_amount']);
            $signal->status = "completed";
            $signal->result = $request->type;
            $signal->save();

            $res['status'] = true;
            $res['message'] = "Trade Completed";
        } catch (\Exception $e) {
            $res['message'] = "Please contact to Administrator";
        }

        return $res;
    }
}
