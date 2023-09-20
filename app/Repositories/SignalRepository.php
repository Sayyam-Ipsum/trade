<?php

namespace App\Repositories;

use App\Interfaces\SignalInterface;
use App\Models\Signal;
use App\Models\Trade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SignalRepository implements SignalInterface
{
    public function listing(Request $request, $id = null)
    {
        if (isset($id)) {
            return Signal::find($id);
        }

        $data = Signal::orderBy("id", "desc");

        if (isset($request->start_date) && isset($request->end_date)) {
            $data = $data->whereBetween(DB::raw('DATE(signals.start_time)'), [$request->start_date, $request->end_date]);
        }

        return $data->get();
    }

    public function getCurrentSignal()
    {
        $currentTime =  Carbon::now('Asia/Karachi')->format('Y-m-d H:i:s');

        return Signal::where("end_time", ">=", $currentTime)->first();
    }

    public function store(Request $request)
    {
        $res['type'] = "error";
        try {
            $checkDuplicateSignal = Signal::where("end_time", ">=", $request->start_date_time)
                ->get();

            if (count($checkDuplicateSignal) > 0) {
                $res['message'] = "Signal Already exists for this time range. Please change time range of signal.";

                return $res;
            }

            DB::beginTransaction();
            $signal = new Signal();
            $signal->type = $request->type;
            $signal->start_time = $request->start_date_time;
            $signal->end_time = $request->end_date_time;
            $signal->amount = $request->amount;
            $signal->save();
            DB::commit();
            $res['type'] = "success";
            $res['message'] = "Signal Created";
        } catch (\Exception $e) {
            DB::rollBack();
            $res['message'] = "Please contact to Administrator";
        }

        return $res;
    }

    public function details($id)
    {
        $details = [
            'buy_trades_total' => 0,
            'buy_trades_sum' => 0,
            'sell_trades_total' => 0,
            'sell_trades_sum' => 0
//            'buy_trades_overprice' => 0,
//            'sell_trades_overprice' => 0
        ];

//        $signal = Signal::find($id);
//
//        if (!$signal)   return $details;

        $data = Trade::where("signal_id", $id)
            ->select(
                "type",
                DB::raw("COUNT(id) as total"),
                DB::raw("SUM(profitable_amount) as sum")
            )
            ->groupBy("type")
            ->get()->toArray();

        if (count($data) < 1)   return $details;

        if (array_key_exists(0, $data)) {
            $details['buy_trades_total'] = $data[0]['total'];
            $details['buy_trades_sum'] = $data[0]['sum'];
        }

        if (array_key_exists(1, $data)) {
            $details['sell_trades_total'] = $data[1]['total'];
            $details['sell_trades_sum'] = $data[1]['sum'];
        }

//        $overPriceTrades = Trade::where("signal_id", $id)
//            ->where("amount", ">", $signal->amount)
//            ->select(
//                "type",
//                DB::raw("COUNT(id) as total"),
//            )
//            ->groupBy("type")
//            ->get()->toArray();
//
//        if (array_key_exists(0, $overPriceTrades)) {
//            $details['buy_trades_overprice'] = $overPriceTrades[0]['total'];
//        }
//
//        if (array_key_exists(1, $overPriceTrades)) {
//            $details['sell_trades_overprice'] = $overPriceTrades[1]['total'];
//        }

        return $details;
    }
}
