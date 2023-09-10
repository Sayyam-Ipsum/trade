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
            if (isset($request->photo)) {
                $photo = $request->file('photo');
                $name = time(
                    ) . '_customer_' . $request->id . '_photo' . '_' . $photo->getClientOriginalName(
                    );
                $photo->move(public_path('uploads/users'), $name);
                $user->photo = '/uploads/users/' . $name;
            }
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
