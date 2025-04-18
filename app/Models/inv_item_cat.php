<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_item_cat extends Model
{
    use HasFactory;

    protected $table = "inv_item_cats";

    protected $primaryKey = "inv_item_cats_id";

    protected $fillable = [
        'company_id',
        'user_id',
        'inv_item_cats_name',
        'inv_item_cats_status'
    ];

    public $timestamps = true;
}
