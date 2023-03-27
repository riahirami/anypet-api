<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    const ACTIVE = 1;
     const INACTIVE = 0;

    protected $fillable = ['title', 'description', 'status'];

  public function scopeByTitle($query, $value){
    return $query->where('title', $value);
}

public function isActive(){
      return $this->status === self::ACTIVE;
}



}
