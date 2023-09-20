<?php

namespace App\Http\Controllers;

use App\Interfaces\SignalInterface;
use App\Interfaces\TradeInterface;
use App\Models\Signal;
use App\Models\Trade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SignalController extends Controller
{
    protected SignalInterface $signalInterface;
    protected TradeInterface $tradeInterface;

    public function __construct(SignalInterface $signalInterface, TradeInterface $tradeInterface)
    {
        $this->signalInterface = $signalInterface;
        $this->tradeInterface = $tradeInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->signalInterface->listing($request);

            return DataTables::of($data)
                ->addColumn('start_time', function ($data) {
                    return showDateTime($data->start_time);
                })
                ->addColumn('end_time', function ($data) {
                    return showDateTime($data->end_time);
                })
//                ->addColumn('type', function ($data) {
//                    return statusBadge($data->type);
//                })
//                ->addColumn('amount', function ($data) {
//                    return '$'.$data->amount;
//                })
//                ->addColumn('status', function ($data) {
//                    return statusBadge($data->status);
//                })
                ->addColumn('result', function ($data) {
                    return statusBadge($data->result);
                })
                ->addColumn('actions', function ($data) {
                    return '<a href="signals/'.$data->id.'" target="_blank" class="btn btn-xs btn-outline-info"><i class="fa fa-eye mr-1"></i>Details</a>';
                })
                ->rawColumns(['actions', 'result'])
                ->make(true);
        }

        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');

        return view("admin.signals.listing", compact(['start_date', 'end_date']));
    }

    public function modal()
    {
        $res['title'] = "Add Signal";
        $res['html'] = view("admin.signals.form")->render();

        return response()->json($res);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "type" => "required",
            "start_date_time" => "required",
            "end_date_time" => "required",
            "amount" => "required"
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

        $res = $this->signalInterface->store($request);

        return redirect()->back()->with($res['type'], $res['message']);
    }

    public function tradesListing(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = $this->tradeInterface->listing($request, $id);

            return DataTables::of($data)
                ->addColumn('user', function ($data) {
                    return $data->user;
                })
                ->addColumn('amount', function ($data) {
                    return '$'.$data->amount;
                })
                ->addColumn('profitable_amount', function ($data) {
                    return '$'.$data->profitable_amount;
                })
                ->addColumn('type', function ($data) {
                    return statusBadge($data->type);
                })
                ->addColumn("result", function ($data) {
                    return statusBadge($data->result);
                })
                ->rawColumns(['type', 'result'])
                ->make(true);
        }

        $signal = $this->signalInterface->listing($id);

        if (!$signal)   abort(404);

        $data = $this->signalInterface->details($id);

        return view("admin.signals.trades", compact(['signal', 'data']));
    }

    public function getSignal()
    {
        return response()->json([
            'status' => true,
            'signal' => $this->signalInterface->getCurrentSignal()
        ]);
    }
}
