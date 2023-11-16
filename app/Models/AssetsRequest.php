<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetsRequest extends Model
{
    use HasFactory, HasUUID;

    public const OPEN = 'Open';
    public const RESOLVED = 'Resolved';
    public const REJECTED = 'Rejected';

    protected $fillable = [
        'asset',
        'requestor_id',
        'request_payload',
        'additional_info',
        'status',
        'commit_amount'
    ];

    protected $casts = [
        'request_payload' => 'array'
    ];

    public function assets()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id');
    }

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id', 'id');
    }
}
