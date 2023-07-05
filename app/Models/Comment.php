<?php

namespace App\Models;

use App\Notifications\AdCommented;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function (Comment $comment) {
            $adOwner = $comment->ad->user;
            $adOwner->notify(new AdCommented($comment->ad));
        });
    }

    public function replyComments()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function scopeAdCommentsList($query, $adId)
    {
        $query = $query->where('ad_id', $adId)->orderBy('created_at', 'desc');
        return $query;
    }

    public static function getAllCommentsWithReplies($adId)
    {
        $comments = Comment::with('user', 'ad','replyComments','replyComments.user')
            ->where('ad_id', $adId)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return $comments;
    }
}
