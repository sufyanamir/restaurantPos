<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_category';

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'company_id',
        'category_name',
        'printer_ip',
        'branch_id',
        'category_image',
        'app_url',
    ];
    
    public $timestamps = true;
}
