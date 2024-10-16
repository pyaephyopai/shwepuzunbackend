<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function scopeBlogFilter($query, $search)
    {
        $query->when($search, function ($q) use ($search) {
            $q->where('title', 'LIKE', '%' . $search . '%');
        });
    }
}
