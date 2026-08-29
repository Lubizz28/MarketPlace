<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BroadcastLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'broadcast_id',
        'user_id',
        'recipient_name',
        'recipient_target',
        'channel',
        'status',
        'error_message',
    ];

    public function broadcast(): BelongsTo
    {
        return $this->belongsTo(BroadcastMessage::class, 'broadcast_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
