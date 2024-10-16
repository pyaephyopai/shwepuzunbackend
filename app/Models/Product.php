<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'category_id',
        'description',
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function scopeProductFilter($query, $search, $category, $minPrice, $maxPrice, $priceSort, $dateSort)
    {
        $query->when($search, function ($q) use ($search) {
            $q->where('name', 'LIKE', '%' . $search . '%');
        });

        $query->when($category, function ($q) use ($category) {

            $categories = explode(',', $category);

            $q->whereIn('category_id', $categories);
        });

        if ($minPrice || $maxPrice) {

            $query->when($minPrice || $maxPrice, function ($q) use ($minPrice, $maxPrice) {

                if ($minPrice && $maxPrice) {

                    $q->whereBetween('price', [$minPrice, $maxPrice]);
                } elseif ($minPrice) {

                    $q->where('price', '>=', $minPrice);
                } elseif ($maxPrice) {

                    $q->where('price', '<=', $maxPrice);
                }
            });
        }

        $query->when($priceSort, function ($q) use ($priceSort) {

            if ($priceSort === 'hightToLow') {
                $q->orderBy('price', 'desc');
            } else {
                $q->orderBy('price', 'ASC');
            }
        });

        $query->when($dateSort, function ($q) use ($dateSort) {

            if ($dateSort === 'newest') {
                $q->orderBy('created_at', 'desc');
            } else {
                $q->orderBy('price', 'ASC');

            }
        });

        return $query;
    }
}
