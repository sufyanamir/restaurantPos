<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalItems extends Model
{
    use HasFactory;

    protected $table = 'order_additional_items';

    protected $primaryKey = 'additional_item_id';

    protected $fillable = [
        'order_id',
        'additional_item_name',
        'additional_item_cost',
    ];

    public $timestamp = true;
}
