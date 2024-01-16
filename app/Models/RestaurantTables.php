<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantTables extends Model
{
    use HasFactory;

    protected $table = 'restaurant_tables';

    protected $primaryKey = 'restaurant_table_id';

    protected $fillable = [
        'company_id',
        'branch_id',
        'table_no',
        'table_capacity',
        'table_location',
        'status',
    ];

    public $timestamps = true;

}
