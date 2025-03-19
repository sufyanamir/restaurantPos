<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $primaryKey = 'inventory_id';

    protected $fillable = [
        'company_id',
        'branch_id',
        'supplier_id',
        'added_user_id',
        'inv_name',
        'inv_stockinhand',
        'inv_unit',
        'inv_box_price',
        'inv_bag_qty',
        'inv_unit_price',
        'low_stock',
        'inv_type',
        'inv_status',
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
