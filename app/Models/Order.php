<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'total_price',
        'payment_id',
        'notes',
        'status',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOrderFilter($query, $search, $status, $payment)
    {

        return $query->when($search, function ($q) use ($search) {
            $q->where('order_code', 'LIKE', '%' . $search . '%');
        })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($payment, function ($q) use ($payment) {
                $q->where('payment_type', $payment);
            });
    }
}
