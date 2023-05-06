<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'driving_license_number',
        'email',
        'phone',
        'address',
        'created_at',
        'updated_at'
        
    ];
}
