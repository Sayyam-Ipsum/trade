<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface WithdrawalInterface
{
    public function listing(Request $request);
    public function updateStatus(Request $request);
    public function storeWithdrawalAccount(Request $request);
    public function storeWithdrawal(Request $request);
    public function withdrawalListing($userID = null);
    public function withdrawalAccountListing($userID);
}
