<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Therapy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'discount_amount',
        'discount_start',
        'discount_end'
    ];

    public function appointments_details(){
        return $this->belongsToMany(Appointment_detail::class);
    }
}
