<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderEditLog extends Model
{
    use HasFactory;

    protected $fillable = ['order_product_id', 'creator_id', 'old_value', 'new_value', 'action'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function order_product()
    {
        return $this->belongsTo(OrdersProducts::class, 'order_product_id');
    }
}
