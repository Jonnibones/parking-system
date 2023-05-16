<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVehicles extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_customer',
        'license_plate_number',
        'brand',
        'model',
        'color',
        
    ];
}
