<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAddOn extends Model
{
    use HasFactory;

    protected $table = 'product_addOn';

    protected $primaryKey = 'addOn_id';

    protected $fillable = [
        'product_id',
        'addOn_name',
        'addOn_price',
    ];

    public $timestamps = true;
}
