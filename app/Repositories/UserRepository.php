<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserInterface
{
    public function listing()
    {
        return User::where("is_admin", 0)->orderBy("id", "desc")->get();
    }

    public function update(Request $request)
    {
        $res['type'] = "error";
        try {
            DB::beginTransaction();
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
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
}
