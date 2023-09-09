<?php

namespace App\Http\Controllers;

use App\Interfaces\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    protected UserInterface $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
    }

    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->userInterface->listing();

            return DataTables::of($data)
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('email', function ($data) {
                    return $data->email;
                })
                ->addColumn('balance', function ($data) {
                    return $data->account_balance;
                })
//                ->addColumn('actions', function ($data) {
//                    $html = "";
//
//                    return $html;
//                })
//                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.users.listing');
    }

    public function profile(Request $request)
    {
        if ($request->post()) {
            $validate = Validator::make($request->all(), [
                "name" => "required",
                "email" => "required"
            ]);

            if ($validate->fails()) {
                return redirect()->back()->withErrors($validate);
            }

            $res = $this->userInterface->update($request);

            return redirect()->back()->with($res['type'], $res['message']);
        }

        return view("admin.dashboard.profile");
    }
}
