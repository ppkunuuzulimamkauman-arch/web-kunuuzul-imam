<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'body',
        'type',
        'attachment_path',
        'attachment_name',
        'attachment_size',
        'attachment_mime',
        'reply_to_id',
        'starred_by',
        'pinned_at',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'pinned_at' => 'datetime',
        'starred_by' => 'array',
        'attachment_size' => 'integer',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function reply(): BelongsTo
    {
        return $this->belongsTo(self::class, 'reply_to_id')->withTrashed();
    }

    public function partnerId(int $meId): int
    {
        return $this->sender_id === $meId ? $this->receiver_id : $this->sender_id;
    }
}
