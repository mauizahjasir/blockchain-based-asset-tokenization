<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetsOnSale extends Model
{
    use HasFactory, HasUUID;

    public const OPEN = 'Open';
    public const CLOSED = 'Closed';

    protected $fillable = [
        'asset',
        'owner_id'
    ];
}
