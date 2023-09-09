<?php

namespace App\Http\Controllers;

use App\Interfaces\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        return redirect()->route('admin-dashboard');
    }

    public function dashboard()
    {
        $data['users'] = 0;
        $data['pending_deposits'] = 0;
        $data['completed_deposits'] = 0;
        $data['completed_withdrawals'] = 0;

        return view('admin.dashboard.dashboard', compact(['data']));
    }


}
