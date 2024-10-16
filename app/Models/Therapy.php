<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Therapy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price'
    ];

    public function appointments_details(){
        return $this->belongsToMany(Appointment_detail::class)
            ->withPivot('discount_start', 'discount_end', 'discount_amount')
            ->withTimestamps();
    }
}
