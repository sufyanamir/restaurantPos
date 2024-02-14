<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAdditionalItems extends Model
{
    use HasFactory;

    protected $table = 'order_additional_items';

    protected $primaryKey = 'additional_item_id';

    protected $fillable = [
        'order_main_id',
        'product_id',
        'title',
        'price',
        'product_qty',
    ];

    public $timestamps = true;

}
