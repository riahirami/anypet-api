<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status','country','state','city','street','postal_code'];

    protected $attributes = [
        'status' => 0,
    ];
    public function category(): HasOne
    {
        return $this->hasOne(Category::class);
    }

    public function scopeByDate($query, $date)
    {
        $formattedDate = date('Y-m-d', strtotime($date));

        return $query->whereDate('created_at', $formattedDate);
    }


}
