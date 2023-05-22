<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable,HasApiTokens;

    protected $fillable = [
        'firstname','lastname', 'email', 'password', 'phone', 'address', 'avatar'
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


    public function role() : HasOne
    {
        return  $this->hasOne(Role::class);
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



}
