<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AssetsRequest extends Model
{
    use HasFactory, HasUUID;

    public const OPEN = 'Open';
    public const RESOLVED = 'Resolved';
    public const REJECTED = 'Rejected';
    public const AWAITING_OWNER_APPROVAL = 'Awaiting Owner Approval';
    public const AWAITING_BUYERS_APPROVAL = 'Awaiting Buyers Approval';
    public const AWAITING_ADMINS_APPROVAL = 'Awaiting Admin Approval';
    public const REJECTED_BY_OWNER = 'Rejected by Owner';
    public const REJECTED_BY_BUYER = 'Rejected by Buyer';

    protected $fillable = [
        'asset',
        'requestor_id',
        'request_payload',
        'additional_info',
        'status',
        'commit_amount',
        'owner_id'
    ];

    protected $casts = [
        'request_payload' => 'array'
    ];

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public static function pendingRequests()
    {
        return static::whereNotIn('status', [static::RESOLVED, static::REJECTED])->get();
    }

    public static function incomingRequests()
    {
        return static::where('owner_id', Auth::user()->id)->whereNotIn('status', [static::RESOLVED, static::REJECTED])->get();
    }

    public static function outgoingRequests()
    {
        return static::where('requestor_id', Auth::user()->id)->whereNotIn('status', [static::RESOLVED, static::REJECTED])->get();
    }

    public function isPending(): bool
    {
        return in_array($this->status, [static::RESOLVED, static::REJECTED, static::AWAITING_BUYERS_APPROVAL]);
    }

    public function isApprovedByOwner(): bool
    {
        return in_array($this->status, [static::AWAITING_ADMINS_APPROVAL, static::AWAITING_BUYERS_APPROVAL]);
    }

    public function isAwaitingAdminsApproval(): bool
    {
        return $this->status === static::AWAITING_ADMINS_APPROVAL;
    }

    public function isAwaitingOwnersApproval(): bool
    {
        return $this->status === static::AWAITING_OWNER_APPROVAL;
    }

    public function isAwaitingBuyersApproval(): bool
    {
        return $this->status === static::AWAITING_BUYERS_APPROVAL;
    }

    public function isResolved(): bool
    {
        return $this->status === static::RESOLVED;
    }

    public function isRejected(): bool
    {
        return $this->status === static::REJECTED;
    }

    public function isRejectedByOwner(): bool
    {
        return $this->status === static::REJECTED_BY_OWNER;
    }

    public function isRejectedByBuyer(): bool
    {
        return $this->status === static::REJECTED_BY_BUYER;
    }

    public function isAwaitingRequestorsApproval(): bool
    {
        return in_array($this->status, [static::AWAITING_OWNER_APPROVAL, static::AWAITING_BUYERS_APPROVAL]);
    }
}
