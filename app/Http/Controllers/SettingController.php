<?php

namespace App\Http\Controllers;

use App\Interfaces\SettingInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    protected SettingInterface $settingInterface;

    public function __construct(SettingInterface $settingInterface)
    {
        $this->settingInterface = $settingInterface;
    }

    public function index()
    {
        $setting = $this->settingInterface->show();

        return view("admin.settings.view", compact(['setting']));
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "withdraw_limit" => "required",
            "referral_amount" => "required",
            "withdrawal_extra_charges_percentage" => "required"
        ]);

        if ($validate->fails()) {
            return redirect(url('admin/settings'))->withErrors($validate);
        }

        $res = $this->settingInterface->store($request);

        return redirect(url('admin/settings'))->with($res["type"], $res["message"]);
    }
}
