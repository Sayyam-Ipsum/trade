<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface SignalInterface
{
    public function listing(Request $request, $id = null);
    public function store(Request $request);
    public function details($id);
    public function getCurrentSignal();
}
