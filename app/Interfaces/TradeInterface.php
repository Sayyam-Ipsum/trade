<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface TradeInterface
{
    public function listing(Request $request);
    public function store(Request $request);
    public function result(Request $request);
    public function liveTrading();
    public function storeSignalResult(Request $request);
}
