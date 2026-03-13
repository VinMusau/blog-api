<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'body',
        'commentable_id',
        'commentable_type',
        'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
