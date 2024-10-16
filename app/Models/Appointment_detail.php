<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'appointment_id',
        'total'
    ];

    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }

    public function therapies(){
        return $this->belongsToMany(Therapy::class)->withTimestamps();
    }

}
