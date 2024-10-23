<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id');
    }

    public function add_ons()
    {
        return $this->hasMany(ProductAddOn::class, 'product_id');
    }


    protected $table = 'products';

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_code',
        'product_name',
        'product_image',
        'product_price',
        'category_id',
        'company_id',
        'app_url',
        'branch_id',
        'favourite_item',
        'product_status',
    ];

    public $timestamps = true;
}
