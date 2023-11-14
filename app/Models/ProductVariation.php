<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $table = 'product_variation';

    protected $primaryKey = 'variation_id';

    protected $fillable = [
        'product_id',
        'variation_name',
        'variation_price',
    ];

    public $timestamps = true;
}
