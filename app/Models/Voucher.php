<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    public function customers()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function transactions()
    {
        return $this->hasMany(Trasanctions::class, 'transaction_id');
    }

    protected $table = 'vouchers';

    protected $primaryKey = 'voucher_id';

    protected $fillable = [
        'voucher_date',
        'credit',
        'debit',
        'transaction_remarks',
        'added_user_id',
        'company_id',
        'branch_id',
        'customer_id',
        'transaction_id',
    ];

    public $timestamps = true;

}
