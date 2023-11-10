<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    use HasFactory, HasUUID;

    protected $fillable = [
        'name',
        'creator_wallet_address',
        'quantity',
        'unit',
        'tx_id',
        'details',
        'asset_type_id',
        'alias'
    ];

    protected $casts = [
        'details' => 'array'
    ];

    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class,  'creator_wallet_address', 'wallet_address');
    }
}
