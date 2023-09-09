<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface SettingInterface
{
    public function show();
    public function store(Request $request);
}
