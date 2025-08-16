<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    public const STATUS_WAITING = 'waiting';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public const STATUSES = [
        self::STATUS_WAITING,
        self::STATUS_IN_PROGRESS,
        self::STATUS_WAITING_FOR_APPROVAL,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    protected $fillable = [
        'lead_id',
        'user_id',
        'product_id',
        'status',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
