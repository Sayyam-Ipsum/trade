<?php

namespace App\Http\Controllers;

use App\Interfaces\SignalInterface;
use App\Models\Signal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SignalController extends Controller
{
    protected SignalInterface $signalInterface;

    public function __construct(SignalInterface $signalInterface)
    {
        $this->signalInterface = $signalInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->signalInterface->listing();

            return DataTables::of($data)
                ->addColumn('start_time', function ($data) {
                    return showDateTime($data->start_time);
                })
                ->addColumn('end_time', function ($data) {
                    return showDateTime($data->end_time);
                })
                ->addColumn('type', function ($data) {
                    return statusBadge($data->type);
                })
                ->addColumn('amount', function ($data) {
                    return $data->amount;
                })
                ->addColumn('actions', function ($data) {
//                    if ($data->result == "pending") {
//                        return '<button class="btn btn-xs btn-outline-success btn-result mr-1" data-id="'.$data->id.'" data-result="profit">Profit</button>
//                                <button class="btn btn-xs btn-outline-danger btn-result mr-1" data-id="'.$data->id.'" data-result="loss">Loss</button>';
//                    }

                    return '';
                })
                ->rawColumns(['type', 'actions'])
                ->make(true);
        }

        return view("admin.signals.listing");
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
}
