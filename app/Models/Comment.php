<?php

namespace App\Models;

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
        $comments = Comment::with('user', 'replyComments','replyComments.user')
            ->where('ad_id', $adId)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return $comments;
    }
}
