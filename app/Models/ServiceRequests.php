<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequests extends Model
{
    use HasFactory;

    protected $table = 'service_requests';

    protected $primaryKey = 'req_id';

    protected $fillable = [
        'req_name',
        'req_company_name',
        'req_email',
        'req_address',
    ];
    public $timestamps = true;
    
}
