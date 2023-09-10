<?php

namespace App\Http\Controllers;

use App\Interfaces\RoleInterface;
use App\Interfaces\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    protected UserInterface $userInterface;
    protected RoleInterface $roleInterface;

    public function __construct(UserInterface $userInterface, RoleInterface $roleInterface)
    {
        $this->userInterface = $userInterface;
        $this->roleInterface = $roleInterface;
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

    public function systemUserListing(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->userInterface->systemUserListing();

            return DataTables::of($data)
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('email', function ($data) {
                    return $data->email;
                })
                ->addColumn('role', function ($data) {
                    return $data->role->name;
                })
                ->addColumn('actions', function ($data) {
                    return '<a href="javascript:void(0);" data-id="' . $data->id . '"
                    class="btn btn-sm btn-edit btn-primary mr-1" ><i class="fas fa-edit mr-1"></i>Edit</a>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view("admin.system-users.listing");
    }

    public function systemUserModal($id = null)
    {
        $editable = false;
        $roles = $this->roleInterface->roleListing();
        if (isset($id)) {
            $editable = true;
            $user = $this->userInterface->listing($id);
            $res["title"] = "Edit System User";
            $res["html"] = view("admin.system-users.form", compact(['user', 'editable', 'roles']))->render();
        } else {
            $res["title"] = "Add System User";
            $res["html"] = view("admin.system-users.form", compact(['editable', 'roles']))->render();
        }

        return response()->json($res);
    }
}
