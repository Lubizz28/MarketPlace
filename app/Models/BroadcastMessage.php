<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BroadcastMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'title',
        'message',
        'target_role',
        'channel',
        'total_recipients',
        'status',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'total_recipients' => 'integer',
            'sent_at' => 'datetime',
        ];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(BroadcastLog::class, 'broadcast_id');
    }
}
