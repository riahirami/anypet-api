<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    const ACTIVE = 1;
     const INACTIVE = 0;

    protected $fillable = ['title', 'description', 'status'];

    public function ads(): HasMany
    {
        return $this->hasMany(Ad::class);
    }

    public function getAds(){
        $ads = Category::find()->ads() ;
    }

  public function scopeByTitle($query, $value){
    return $query->where('title', $value);
}

public function isActive(){
      return $this->status === self::ACTIVE;
}



}
