<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    public function order_items()
    {
        return $this->hasMany(OrderItems::class, 'order_main_id');
    }

    public function additional_items()
    {
        return $this->hasMany(OrderAdditionalItems::class, 'order_main_id');
    }

    protected $table = 'orders';

    protected $primaryKey = 'order_main_id';

    protected $fillable = [
        'added_user_id',
        'order_id',
        'order_no',
        'order_type',
        'order_sub_total',
        'order_discount',
        'order_grand_total',
        'order_final_total',
        'order_sale_tax',
        'service_charges',
        'order_change',
        'order_split',
        'order_split_amount',
        'is_uploaded',
        'customer_name',
        'phone',
        'assign_rider',
        'customer_address',
        'table_id',
        'table_location',
        'table_no',
        'table_capacity',
        'branch_id',
        'waiter_id',
        'waiter_name',
        'status',
        'company_id',
        'user_branch_id',
        'customer_id',
        'updatedOrder',
        'order_history',
        'order_date_time',
    ];

    public $timestamps = true;

}
