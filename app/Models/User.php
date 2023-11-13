<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'wallet_address'
    ];

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

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_has_roles');
    }

    public function assetsRequests()
    {
        return $this->hasMany(AssetsRequest::class, 'requestor_id', 'id');
    }

    public function isAdmin(): bool
    {
        return !empty($this->roles->where('title', Role::ADMIN)?->first());
    }

    public function isVerified()
    {
        return !empty($this->email_verified_at);
    }

    public function myBalance()
    {
        $multichain = app('multichainService');

        $addressBalances = $multichain->multichain()->getaddressbalances($this->wallet_address);
        $currency =  config('multichain.currency');
        $walletBalance = collect($addressBalances)->where('name', $currency)->first();

        return empty($walletBalance) ? "0 $currency" : $walletBalance['qty'] . " $currency";
    }
}
