<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
        'read_at',
        'replied_at',
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'read_at'    => 'datetime',
        'replied_at' => 'datetime',
    ];

    /**
     * Scope: only unread messages.
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }
}
