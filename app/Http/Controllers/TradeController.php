<?php

namespace App\Http\Controllers;

use App\Interfaces\TradeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TradeController extends Controller
{
    protected TradeInterface $tradeInterface;

    public function __construct(TradeInterface $tradeInterface)
    {
        $this->tradeInterface = $tradeInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->tradeInterface->listing($request);

            return DataTables::of($data)
                ->addColumn('user', function ($data) {
                    return $data->user;
                })
                ->addColumn('amount', function ($data) {
                    return $data->amount;
                })
                ->addColumn('profitable_amount', function ($data) {
                    return $data->profitable_amount;
                })
                ->addColumn('type', function ($data) {
                    return statusBadge($data->type);
                })
                ->addColumn("status", function ($data) {
                    return statusBadge($data->status);
                })
                ->addColumn('actions', function ($data) {
                    if ($data->result == "pending") {
                        return '<button class="btn btn-xs btn-outline-success btn-result mr-1" data-id="'.$data->id.'" data-result="profit">Profit</button>
                                <button class="btn btn-xs btn-outline-danger btn-result mr-1" data-id="'.$data->id.'" data-result="loss">Loss</button>';
                    }

                    return statusBadge($data->result);
                })
                ->rawColumns(['type', 'status', 'actions'])
                ->make(true);
        }

        $start_date = date('Y-m-d',strtotime('-1 year'));
        $end_date = date('Y-m-d', strtotime('+1 year'));

        return view("admin.trades.listing", compact(['start_date', 'end_date']));
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "user_id" => "required",
            "signal_id" => "required",
            "type" => "required",
            "amount" => "required",
            "profitable_amount" => "required"
        ]);

        if ($validate->fails()) {
            dd($validate->getMessageBag());
            return response()->json([
                "status" => false,
                "message" => "Validation Error"
            ]);
        }

        $res = $this->tradeInterface->store($request);

        return response()->json([
            "status" => $res['status'],
            "message" => $res['message']
        ]);
    }

    public function result(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id" => "required",
            "result" => "required"
        ]);

        if ($validate->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation Error"
            ]);
        }

        $res = $this->tradeInterface->result($request);

        return response()->json([
            "status" => $res['status'],
            "message" => $res['message']
        ]);
    }

    public function liveTrading(Request $request)
    {
        if($request->ajax()){
            $signals = $this->tradeInterface->liveTrading();

            return response()->json(['data' => $signals]);
        }

        return view("admin.live-trading.view");
    }

    public function liveTradingResult(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "type" => "required",
            "signal_id" => "required",
            "result" => "required"
        ]);

        if ($validate->fails()) {
            $res['status'] = false;
            $res['message'] = "Validation Error";

            return response()->json($res);
        }

        $res = $this->tradeInterface->storeSignalResult($request);

        return response()->json($res);
    }

    public function tradeHistory(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->tradeInterface->getTrades();

            return DataTables::of($data)
                ->addColumn('created_at', function ($data) {
                    return showDate($data->created_at);
                })
                ->addColumn('amount', function ($data) {
                    return '$'.$data->amount;
                })
                ->addColumn('type', function ($data) {
                    return statusBadge($data->type);
                })
                ->addColumn("status", function ($data) {
                    return statusBadge($data->status);
                })
                ->addColumn('result', function ($data) {
                    if ($data->result == "profit") {
                        return '<button class="btn btn-sm btn-success px-2"><i class="far fa-plus mr-1" style="font-size: 11px;"></i>$'.($data->profitable_amount - $data->amount).'<span class="ml-1">Profit</span></button>';
                    } elseif ($data->result == "loss") {
                        return '<button class="btn btn-sm btn-danger px-2"><i class="far fa-minus mr-1" style="font-size: 11px;"></i>$'.$data->amount.'<span class="ml-1">Loss</span></button>';
                    } else {
                        return '-';
                    }
                })
                ->rawColumns(['type', 'status', 'result'])
                ->make(true);
        }

        return view('site.trade.history');
    }

    public function getTrades($filter = null)
    {
        return response()->json([
            'status' => true,
            'data' => $this->tradeInterface->getTrades($filter)
        ]);
    }
}
