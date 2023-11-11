<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyExpense extends Model
{
    use HasFactory;
    protected $table = 'company_expense';

    protected $primaryKey = 'expense_id';

    protected $fillable = [
        'added_user_id',
        'company_id',
        'expense_date',
        'expense_name',
        'expense_cost',
    ];
    public $timeStamps = true;
}
