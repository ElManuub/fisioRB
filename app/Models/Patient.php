<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'office_id'
    ];
    
    public function appointments(){
        return $this->hasMany(Appointment::class);
    }

    public function office(){
        return $this->belongsTo(Office::class);
    }
}
