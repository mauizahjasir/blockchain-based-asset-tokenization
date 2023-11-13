<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetsRequest extends Model
{
    use HasFactory, HasUUID;

    public const UNDER_REVIEW = 'Under Review';
    public const RESOLVED = 'Resolved';
    public const REJECTED = 'Rejected';

    protected $fillable = [
        'asset_id',
        'requestor_id',
        'commit_amount',
        'additional_info',
        'request_payload'
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
