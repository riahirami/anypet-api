<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'address', 'logo', 'contact', 'link'
    ];

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
