<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteAd extends Model
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

    public function scopeFavoriteList($query, $userId)
    {
        $query = $query->whereHas('ad', function ($query) {
            $query->whereIn('status', [2, 3, 4]);
        })->where('user_id', $userId)
            ->with('ad.media', 'ad.user');

        return $query;
    }
}
