<?php

namespace App\Models;

use App\Notifications\AdMatchingInterrestNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'phone', 'address', 'avatar'
    ];

    protected $attributes = [
        'role_id' => "1",
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function ads(): HasMany
    {
        return $this->hasMany(Ad::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }


    public function role(): HasOne
    {
        return $this->hasOne(Role::class);
    }

    public function messages()
    {
        return $this->hasMany(Messages::class, 'sender_id');
    }

    public function sentReservations()
    {
        return $this->hasMany(Reservation::class, 'sender_id');
    }

    public function receivedReservations()
    {
        return $this->hasMany(Reservation::class, 'receiver_id');
    }



    public function userNotifications(): MorphMany
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
            ->orderBy('created_at', 'desc');
    }

    public function scopeWithNotifications($query)
    {
        return $query->with(['userNotifications' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function scopeVerifiedEmail($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function favoriteAds()
    {
        return $this->belongsToMany(Ad::class, 'favorite_ads')->withTimestamps();
    }

    public function notifyIfNewAdInFavoriteCategoryAdded(Ad $ad)
    {
        // Check if the user has a favorite ad in the same category as the new ad
        $hasFavoriteAdInCategory = $this->favoriteAds()
            ->where('category_id', $ad->category_id)
            ->exists();

        if ($hasFavoriteAdInCategory) {
            // Notify the user about the new ad in their favorite category
            $this->notify(new AdMatchingInterrestNotification($ad));
        }
    }


}
