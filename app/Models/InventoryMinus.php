<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMinus extends Model
{
    use HasFactory;

    protected $table = 'inventory_minus';

    protected $primaryKey = 'inv_m_id';

    protected $fillable = [
        'company_id',
        'branch_id',
        'dpt_name',
        'added_user_id',
        'inv_m_date',
        'dpt_phone',
        'dpt_note',
        'inv_order_details',
        'inv_m_total',
        'inv_m_paid',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

}
