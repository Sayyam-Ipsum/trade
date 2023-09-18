<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface SignalInterface
{
    public function listing($id = null);
    public function store(Request $request);
    public function details($id);
    public function getSignalsForLiveTrading();
}
