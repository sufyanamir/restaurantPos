<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPlus extends Model
{
    use HasFactory;

    protected $table = 'inventory_plus';

    protected $primaryKey = 'inv_p_id';

    protected $fillable = [
        'company_id',
        'branch_id',
        'supplier_id',
        'added_user_id',
        'inv_p_date',
        'supplier_phone',
        'supplier_note',
        'inv_order_details',
        'inv_p_total',
        'inv_p_paid',
    ];

    public $timestamps = true;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(CompanyBranch::class, 'branch_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Customers::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

}
