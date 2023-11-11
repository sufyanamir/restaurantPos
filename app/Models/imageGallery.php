<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class imageGallery extends Model
{
    use HasFactory;
    
    protected $table = 'image_gallery';

    protected $primaryKey = 'image_id';

    protected $fillable = [
        'added_user_id',
        'staff_id',
        'company_id',
        'customer_id',
        'order_id',
        'stored_media',
        'app_url',
        'media_type'
    ];

    public $timestamps = true;
}
