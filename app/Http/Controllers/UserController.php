<?php

namespace App\Http\Controllers;

use App\Interfaces\RoleInterface;
use App\Interfaces\UserInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

/**
 *
 */
class UserController extends Controller
{
    /**
     * @var UserInterface
     */
    protected UserInterface $userInterface;

    /**
     * @var RoleInterface
     */
    protected RoleInterface $roleInterface;

    /**
     * @param UserInterface $userInterface
     * @param RoleInterface $roleInterface
     */
    public function __construct(UserInterface $userInterface, RoleInterface $roleInterface)
    {
        $this->userInterface = $userInterface;
        $this->roleInterface = $roleInterface;
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
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

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|RedirectResponse
     */
    public function profile(Request $request)
    {
        if ($request->post()) {
            $validate = Validator::make($request->all(), [
                "name" => "required",
                "phone_number" => "required | digits:11"
            ]);

            if ($validate->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validate);
            }

            $res = $this->userInterface->update($request);

            return redirect()
                ->back()
                ->with($res['type'], $res['message']);
        }

        return view("admin.dashboard.profile");
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
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

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param Request $request
     * @param $id
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function storeSystemUser(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | max:50',
            "email" => [
                "required", "email", "max:200",
                Rule::unique("users", "email")
                    ->ignore($id,'id')
            ],
            'password' => 'sometimes | required | min:8',
            'role' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect(url('admin/system-users'))->withErrors($validator->errors());
        }

        $response = $this->userInterface->storeSystemUser($request, $id);

        return redirect('admin/system-users')->with($response['type'], $response['message']);
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => 'required | max:12 | min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $response = $this->userInterface->changePassword($request);

        return redirect()->back()->with($response['type'], $response['message']);
    }

}
