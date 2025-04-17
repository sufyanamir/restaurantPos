<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_unit extends Model
{
    use HasFactory;

    protected $table =  "inv_units";

    protected $primaryKey = "inv_unit_id";

    protected $fillable = [
        "company_id",
        "user_id",
        "inv_unit_name",
        "inv_unit_symbol",
        "inv_unit_status",

    ];

    public $timestamps = true;
}
