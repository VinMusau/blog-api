<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category_id'
    ];
    // create relation to user
    public function user()
    {
        return $this->belongsTo(User::class); // each post belongs to a user
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    protected $appends = ['is_liked'];

    public function getIsLikedAttribute()
    {
        $userId = auth()->id();
        if (!$userId) {
            return false; // User is not authenticated, so they can't like the post
        }
         return $this->likes()->where('user_id', $userId)->exists();
    }
}
