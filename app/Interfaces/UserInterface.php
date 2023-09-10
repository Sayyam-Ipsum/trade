<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface UserInterface
{
    public function listing($id = null);
    public function update(Request $request);
    public function systemUserListing();
}
