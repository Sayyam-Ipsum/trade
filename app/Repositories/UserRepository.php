<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class UserRepository implements UserInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function listing($id = null)
    {
        if (isset($id)) {
            return User::find($id);
        }

        return User::where("is_admin", 0)
            ->orderBy("id", "desc")
            ->get();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function update(Request $request): array
    {
        $res['type'] = "error";
        try {
            DB::beginTransaction();
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            if (isset($request->photo)) {
                $photo = $request->file('photo');
                $name = time() . '_customer_' . $request->id . '_photo' . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('uploads/users'), $name);
                $user->photo = '/uploads/users/' . $name;
            }
            $user->password = password_hash($request->password, PASSWORD_DEFAULT);
            $user->save();
            DB::commit();
            $res['type'] = "success";
            $res['message'] = "Profile Updated Successfully";
        } catch (\Exception $e) {
            DB::rollBack();
            $res['message'] = "Internal Server Error";
        }

        return $res;
    }

    /**
     * @return Builder[]|Collection
     */
    public function systemUserListing(): Collection|array
    {
        return User::with("role")
            ->join("roles", "users.role_id", "roles.id")
            ->where("is_admin", 1)
            ->where("roles.name", "<>", "Super Admin")
            ->select("users.id", "users.name", "users.email", "role_id")
            ->orderBy("users.id")
            ->get();
    }

    /**
     * @param $request
     * @param $id
     * @return array
     */
    public function storeSystemUser($request, $id): array
    {
        $response['type'] = 'error';
        try {
            DB::beginTransaction();
            $user = $id ? User::find($id) : new User();
            $user->email = $request->email;
            $user->name = $request->name;
            $user->password = password_hash($request->password, PASSWORD_DEFAULT);
            $user->role_id = $request->role;
            $user->is_admin = 1;
            $user->save();
            DB::commit();
            $response['type'] = 'success';
            $response['message'] = "System User Added";
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            $response['message'] = "Something went wrong, please try again";
        }

        return $response;
    }

    public function changePassword($request)
    {
        $response['type'] = 'error';
        try {
            DB::beginTransaction();
            User::where('id', $request->id)->update(['password' => password_hash($request->password, PASSWORD_DEFAULT)]);
            DB::commit();
            $response['type'] = 'success';
            $response['message'] = "Password Changed";
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            $response['message'] = "Something went wrong, please try again";
        }

        return $response;
    }
}
