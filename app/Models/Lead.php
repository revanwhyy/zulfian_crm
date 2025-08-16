<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Normalize phone to digits-only when setting.
     */
    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = preg_replace('/\D+/', '', (string) $value);
    }
}
