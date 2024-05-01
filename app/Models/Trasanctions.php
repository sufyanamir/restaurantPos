<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trasanctions extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'company_id',
        'branch_id',
        'added_user_id',
        'customer_id',
        'order_id',
        'debit_amount',
        'credit_amount',
        'transaction_added_from',
    ];

    public $timestamps = true;

}
