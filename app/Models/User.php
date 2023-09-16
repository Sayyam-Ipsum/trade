<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected function accountBalance(): Attribute
    {
        return Attribute::make(
            get: fn($value) => round($value, 2),
            set: fn($value) => round($value, 2),
        );
    }

    public function role()
    {
        return $this->belongsTo(Role::class, "role_id");
    }

    public function countDeposit(): int
    {
        return $this->belongsTo(Deposit::class, "user_id")
            ->where("status", "approved")
            ->count();
    }

    public function countWithdraw(): int
    {
        return $this->belongsTo(Withdrawal::class, "user_id")
            ->where("status", "approved")
            ->count();
    }

    public function countTrades(): int
    {
        return $this->belongsTo(Trade::class, "user_id")
            ->where("status", "completed")
            ->count();
    }

    public function countReferral(): int
    {
        return $this->belongsTo(Referral::class, "referred_by")->count();
    }
}
