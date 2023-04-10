<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status','country','state','city','street','postal_code', 'category_id'];


    public function scopeByDate($query, $date)
    {
        $formattedDate = date('Y-m-d', strtotime($date));

        return $query->whereDate('created_at', $formattedDate);
    }

    public function scopeByCategory($query, $id)
    {
        return $query->where('category_id', $id);
    }
}
