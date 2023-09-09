<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $table = "deposits";
    protected $guarded = [];

    protected function photo(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => url('').$value,
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, "payment_method_id");
    }
}
