<?php

namespace App\Repositories;

use App\Interfaces\SettingInterface;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingRepository implements SettingInterface
{
    public function show()
    {
        return Setting::first();
    }

    public function store(Request $request)
    {
        $res["type"] = "error";
        try {
            DB::beginTransaction();
            $setting = isset($request->id) ? Setting::find($request->id) : new Setting();
            $setting->withdraw_limit = $request->withdraw_limit;
            $setting->referral_amount = $request->referral_amount;
            $setting->save();
            DB::commit();
            $res["type"] = "success";
            $res["message"] = "Settings saved successfully";
        } catch (\Exception $e) {
            DB::rollBack();
            $res["message"] = "Internal Server Error";
        }

        return $res;
    }
}
