<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }


    protected $table = 'customers';

    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'company_id',
        'branch_id',
        'added_user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'opening_balance',
    ];

    public $timestamps = true;
}
