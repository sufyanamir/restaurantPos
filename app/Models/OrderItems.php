<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'order_main_id',
        'product_id',
        'product_qty',
        'product_price',
        'product_variations',
        'product_add_ons',
    ];

    public $timestamps = true;

}
