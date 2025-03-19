<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
    use HasFactory;

    protected $table = 'staff_attendance';

    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'company_id',
        'branch_id',
        'added_user_id',
        'attendance_date',
        'attendance_details',
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
