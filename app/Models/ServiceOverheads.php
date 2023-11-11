<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOverheads extends Model
{
    use HasFactory;

    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

    protected $table = 'services_overheads';

    protected $primaryKey = 'overhead_id';

    protected $fillable = [
        'service_id',
        'overhead_name',
        'overhead_cost',
    ];

    public $timestamps = true;
}
