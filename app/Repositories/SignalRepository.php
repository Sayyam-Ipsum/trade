<?php

namespace App\Repositories;

use App\Interfaces\SignalInterface;
use App\Models\Signal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SignalRepository implements SignalInterface
{
    public function listing($id = null)
    {
        if (isset($id)) {
            return Signal::find($id);
        }

        return Signal::orderBy("id", "desc")->get();
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
}
