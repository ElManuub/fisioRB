<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Jose Manuel';
        $user->email = 'puedose_muy@hotmail.com';
        $user->password = Hash::make('n18n29i58');
        $user->role = 'administrador';
        $user->office_id = 1;
        $user->save();
    }
}
