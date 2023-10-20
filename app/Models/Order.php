<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'creator_id',
        'level1_manager_id',
        'level2_manager_id',
        'level1_manager_approved_result',
        'level2_manager_approved_result',
        'status',
        'delivery_date'
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function level1_manager()
    {
        return $this->belongsTo(User::class, 'level1_manager_id');
    }

    public function level2_manager()
    {
        return $this->belongsTo(User::class, 'level2_manager_id');
    }

    public function getProductCntAttribute()
    {
        $ordersproducts = OrdersProducts::where('order_id', $this->id)->get();
        return $ordersproducts->count();
    }

    public function getTotalWeightAttribute()
    {
        $ordersproducts = OrdersProducts::where('order_id', $this->id)->get();
        $total_weight = 0;
        foreach($ordersproducts as $item){
            $total_weight += $item->quantity;
        }
        return $total_weight;
    }
}
