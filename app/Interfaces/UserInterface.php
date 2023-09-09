<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface UserInterface
{
    public function listing();
    public function update(Request $request);
}
