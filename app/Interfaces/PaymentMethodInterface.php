<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PaymentMethodInterface
{
    public function paymentMethodListing($active = false);
    public function storePaymentMethod(Request $request);
    public function updateStatus(Request $request);
    public function getPaymentMethodDetail($id);
}
