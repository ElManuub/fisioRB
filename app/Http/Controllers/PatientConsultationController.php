<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Appointment_detail;
use Illuminate\Validation\ValidationException;

class PatientConsultationController extends Controller
{
    public function index(Request $request)
    {
        //dd($request);
        try {
            $request->validate([
                'consultation_patient_id' => 'integer|nullable',
                'consultation_date_start' => 'nullable|date',
                'consultation_date_end' => 'nullable|date|after_or_equal:consultation_date_start'
            ]);

            // Obtener todas las consultas junto con las terapias relacionadas
            $consultations = Appointment_detail::whereBetween('date', [$request->consultation_date_start, $request->consultation_date_end])
                ->with(['appointment.patient.office', 'appointment.user', 'therapies'])->get();

            // $consultations = Appointment_detail::all(); 

            return response()->json([
                'data' => $consultations,
                'error' => null,
                'message' => 'consultas encontradas con exito'
            ], 200);
        } catch (ValidationException $err) {

            return response()->json([
                'data' => null,
                'error' => $err->validator->errors(),
                'message' => 'Hubo un error, favor de revisar que sus datos sean correctos'
            ], 404);
        } catch (\Throwable $th) {

            return response()->json([
                'data' => null,
                'error' => $th->getMessage(),
                'message' => 'sin respueta, hubo un error de servidor.'
            ], 500);
        }
    }

    public function ticket($id)
{
    $consultationDetail = Appointment_detail::with(['appointment.patient.office', 'appointment.user', 'therapies'])
        ->find($id);

    if (!$consultationDetail) {
        return response()->json(['error' => 'Detalle de cita no encontrado'], 404);
    }

    $appointment = $consultationDetail->appointment;

    $data = [
        'consultationDetail' => $consultationDetail,
        'appointment' => $appointment,
        'patient' => $appointment->patient,
        'office' => $appointment->patient->office,
        'user' => $appointment->user,
        'total' => $consultationDetail->total,
        'extra' => $consultationDetail->extra,
        'query_type' => $consultationDetail->query_type
    ];

    $pdf = Pdf::loadView('exports_tables.ticket', $data);

    //dd($data);

    return $pdf->stream('Comprobante_' . now()->format('Y-m-d') . '.pdf');
}


}
