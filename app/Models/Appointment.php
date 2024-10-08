<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'note',
        'start_time',
        'end_time',
        'user_id',
        'patient_id',
        'status'
    ];

    public function appointment_details(){
        return $this->hasMany(Appointment_detail::class);
    }
    public function patient(){
        return $this->belongsTo(Patient::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

}
