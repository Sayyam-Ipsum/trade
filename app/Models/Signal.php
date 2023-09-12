<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signal extends Model
{
    use HasFactory;

    protected $table = "signals";
    protected $guarded = [];

    public function trades()
    {
        return $this->hasMany(Trade::class, "signal_id");
    }
}
