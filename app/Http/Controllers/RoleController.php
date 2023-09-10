<?php

namespace App\Http\Controllers;

use App\Interfaces\RoleInterface;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    protected RoleInterface $roleInterface;

    public function __construct(RoleInterface $roleInterface)
    {
        $this->roleInterface = $roleInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = $this->roleInterface->roleListing();

            return DataTables::of($roles)
                ->addColumn('name', function ($roles) {
                    return $roles->name;
                })
                ->addColumn('actions', function ($roles) {
                    return '<a href="javascript:void(0);" data-id="' . $roles->id . '"
                    class="btn btn-sm btn-edit btn-primary mr-1" ><i class="fas fa-edit mr-1"></i>Edit</a>
                    <a href="javascript:void(0);" data-id="' . $roles->id . '"
                    class="btn btn-sm btn-permission btn-warning mr-1" ><i class="fas fa-shield-alt mr-1"></i>Permissions</a>';
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }

        return view('admin.roles.listing');
    }

    public function modal($id = null){
        if($id){
            $res["title"]   = 'Edit Role';
            $role = Role::find($id);
            $res["html"]    = view('admin.roles.form', compact(['role']))->render();
        }
        else{
            $res["title"]   = 'Add New Role';
            $res["html"]    = view('admin.roles.form')->render();
        }

        return response()->json($res);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if (!$validator->fails()) {
            $res = $this->roleInterface->storeRole($request);
            if ($res) {
                $msg = $request->id ? 'Role Updated' : 'Role Added';
                return redirect('admin/roles')->with('success', $msg);
            } else {
                return redirect('admin/roles')->with('error', 'Something went wrong');
            }
        } else {
            return redirect(url('admin/roles'))->withErrors($validator->errors());
        }
    }

    public function permissionModal($id)
    {
        $role = Role::find($id);
        $res["title"]   = $role->name . ' Permissions';
        $res["html"]    = view('admin.roles.permissions', compact(['role']))->render();

        return response()->json($res);
    }
}
