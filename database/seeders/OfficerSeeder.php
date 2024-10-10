<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officer = new Office();
        $officer->name = 'Chapalita';
        $officer->address = 'Del parque #440';
        $officer->town = 'Zapopan Jal.';
        $officer->colony = 'chapalita oriente';
        $officer->postalCode = '45040';
        $officer->phone_number = '3320586138';
        $officer->save();
    }
}
