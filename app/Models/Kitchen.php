<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kitchen extends Model
{
    use HasFactory;

    protected $table = 'kitchen';

    protected $primaryKey = 'kitchen_id';

    protected $fillable = [
        'company_id',
        'branch_id',
        'kitchen_name',
        'printer_ip',
    ];

    public $timestamps = true;

}
