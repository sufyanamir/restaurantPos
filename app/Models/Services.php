<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;

    public function overheads()
    {
        return $this->hasMany(ServiceOverheads::class, 'service_id');
    }

    public function serviceOverheads()
    {
        return $this->hasMany(ServiceOverheads::class, 'service_id', 'service_id');
    }

    protected $table = 'services';

    protected $primaryKey = 'service_id';

    protected $fillable = [
        'service_name',
        'service_subtitle',
        'service_charges',
        'service_desc',
        'service_image',
        'added_user_id',
        'company_id',
        'service_duration',
        'app_url'
    ];
    public $timestamps = true;
}
