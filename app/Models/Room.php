<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'location',
        'price',
        'owner_id',
        'image',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(RoomDetail::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(RoomDiscussion::class);
    }

    public function scopeOwnedBy(Builder $query, $userId): void
    {
        $query->where('owner_id', $userId);
    }
}
