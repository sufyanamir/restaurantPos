<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBranch extends Model
{
    use HasFactory;
    
    protected $table = 'company_branches';

    protected $primaryKey = 'branch_id';

    protected $fillable = [
        'company_id',
        'branch_code',
        'branch_name',
        'branch_email',
        'branch_phone',
        'branch_address',
        'branch_manager',
        'branch_status',
    ];

    public $timestamps = true;
}
