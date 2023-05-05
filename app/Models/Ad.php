<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status', 'country', 'state', 'city', 'street', 'postal_code', 'category_id'];

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
        if(isset($date))
         $query=$query->whereDate('created_at', $formattedDate);

        return $query ;
    }

    public function scopeByCategory($query, $id)
    {
        return $query->where('category_id', $id);
    }

    public function scopeByStatus($query, $status)
    {
        if(isset($status))
        return $query->where('status', $status);
    }

    public function scopeByKeyword($query, $keyword)
    {
        if(isset($keyword))
        $query = $query->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('description', 'LIKE', '%' . $keyword . '%');
        return $query;
    }

    public function scopeAdsStats($query, $column)
    {
        return $query->select($column, $query->raw('count(*) as total'))
            ->groupBy($column)
            ->get();
    }

    public function scopeCountAdsPerDate($query){
        return $query->select($query->raw('DATE(created_at) as date'), $query->raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }


}
