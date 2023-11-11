<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected static function boot()
{
    parent::boot();

    static::deleting(function ($company) {
        // Delete related users
        $company->users()->delete();

        // Delete related customers
        $company->customers()->delete();
    });
}

public function users()
{
    return $this->hasMany(User::class, 'company_id');
}

public function customers()
{
    return $this->hasMany(Customers::class, 'company_id');
}



    protected $table = 'company';

    protected $primaryKey = 'company_id';

    protected $fillable = [
        'company_name',
        'company_email',
        'company_phone',
        'company_address',
        'company_image',
        'app_url',
        'fb_acc',
        'ig_acc',
        'tt_acc',
    ];

    public $timestamps = true;
}
