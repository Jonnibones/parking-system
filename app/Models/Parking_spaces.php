<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parking_spaces extends Model
{
    use HasFactory;

    protected $fillable = [
        'parking_space_number',
        'description',
        'created_at',
        'updated_at'
        
    ];
}
