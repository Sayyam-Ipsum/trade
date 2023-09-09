<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface DepositInterface
{
    public function listing(Request $request);
    public function updateStatus(Request $request);
    public function storeDeposit(Request $request);
    public function depositListing($userID = null);
}
