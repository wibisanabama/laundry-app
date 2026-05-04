<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id', 'user_id', 'status', 'payment_status', 
        'payment_method', 'discount', 'total_amount', 'paid_amount', 
        'estimated_completion_date', 'notes'
    ];

    protected $casts = [
        'estimated_completion_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
