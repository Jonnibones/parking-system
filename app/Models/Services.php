<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_customer',
        'driver_name',
        'driving_license_number',
        'license_plate_number',
        'vehicle_brand', 
        'vehicle_model',
        'vehicle_color',
        'service_type',
        'entry_time',
        'departure_time',
        'created_at',
        'uploaded_at',
    ];
}
