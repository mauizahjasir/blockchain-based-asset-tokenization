<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetsRequest extends Model
{
    use HasFactory, HasUUID;

    protected $fillable = [
      'asset_id',
      'requestor_id'
    ];

    public function assets()
    {
        return $this->belongsTo(Asset::class,  'asset_id', 'id');
    }

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id', 'id');
    }
}
