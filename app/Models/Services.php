<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_customer',
        'id_parking_space',
        'driver_name',
        'driver_phone_number',
        'driver_email',
        'driving_license_number',
        'license_plate_number',
        'vehicle_brand', 
        'vehicle_model',
        'vehicle_color',
        'service_type',
        'service_code',
        'entry_time',
        'departure_time',
        'value',
        'status',
        'created_at',
        'uploaded_at',
    ];
}
