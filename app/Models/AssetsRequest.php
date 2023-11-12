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
}
