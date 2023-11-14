<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAsset extends Model
{
    use HasFactory, HasUUID;

    protected $fillable = [
        'asset_id',
        'owner_id',
        'previous_owner_id',
        'tx_id'
    ];
}
