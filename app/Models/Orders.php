<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id', 'order_id');
    }

    public function orderAdditionalItems()
    {
        return $this->hasMany(AdditionalItems::class, 'order_id', 'order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'customer_id');
    }

    protected $table = 'orders';

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'customer_id',
        'company_id',
        'start_date',
        'end_date',
        'order_additional_items_total',
        'order_discount',
        'order_remarks',
        'order_total',
        'order_status',
        'payment_receipt',
        'sub_total',
        'total_duration',
        'app_url',
        'payment_reciept',
    ];

    public $timestamps = true;
}
