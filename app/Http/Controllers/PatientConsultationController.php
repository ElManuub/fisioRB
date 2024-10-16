<?php
namespace App\Http\Controllers;

use App\Models\Appointment_detail;
use Illuminate\Http\Request;

class PatientConsultationController extends Controller
{
    public function index()
    {
        // Obtener todas las consultas junto con las terapias relacionadas
        $consultations = Appointment_detail::with('therapies')->get();

        // Retornar una vista con las consultas
        return response()->json($consultations);
    }
}
