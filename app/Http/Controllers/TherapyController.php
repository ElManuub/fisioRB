<?php

namespace App\Http\Controllers;

use App\Models\Therapy;
use Illuminate\Http\Request;

class TherapyController extends Controller
{
    public function index(){
        $therapies = Therapy::all();
        //dd($therapies);
        return view('therapies')->with('therapies', $therapies);
    }
}
