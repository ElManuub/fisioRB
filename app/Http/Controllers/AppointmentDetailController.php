<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Appointment_detail;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;

class AppointmentDetailController extends Controller
{
    public function index()
    {
        return view('details');
    }

    public function show(Request $request)
    {
        try {
            $request->validate(['date' => 'date|required|date']);
            $patients = Appointment::whereDate('date', $request->date)
                ->with([
                    'patient',
                    'user'
                ])
                ->get()
                ->map(function ($patient) {
                    return [
                        'id' => $patient->id,
                        'date' => $patient->date,
                        'note' => $patient->note,
                        'start_time' => $patient->start_time,
                        'end_time' => $patient->end_time,
                        'physiotherapist' => $patient->user->name,
                        'patient' => $patient->patient->name,
                        'status' => $patient->status
                    ];
                });

            return view('details')->with('appointments', $patients);
        } catch (ValidationException $err) {
            return "Error en validacion: " . $err->getMessage();
        } catch (Exception $err) {
            return "Hubo un error en la peticion: " . $err->getMessage();
        }
    }

    public function showDetails($id)
    {
        $patient = Appointment::find($id);

        $patient->load(['patient', 'user']);

        $details = [
            'id' => $patient->id,
            'date' => $patient->date,
            'note' => $patient->note,
            'physiotherapist' => $patient->user->name,
            'patient' => $patient->patient->name,
            'phone_number' => $patient->patient->phone_number,
            'appointment_id' => $id
        ];


        return view('appointment_detail')->with('patient', $details);
    }

    public function total(Request $request)
    {
        //dd($request);

        try {
            // Validar los datos de entrada
            $request->validate(
                [
                    'date' => 'required|date',
                    'appointment_id' => 'required|integer',
                    'total' => 'required|numeric|min:1'
                ],
                [
                    'total.min' => 'No puedes dejar vacio el campo total.'
                ]
            );

            // Comprobar si ya existe el appointment_id en la tabla appointment_details
            $existingDetail = Appointment_detail::where('appointment_id', $request->appointment_id)->first();

            if ($existingDetail) {
                // Si ya existe un registro con ese appointment_id, redirigir con un mensaje de error
                return redirect()->back()->with('error', 'Ya existe un registro de esta cita.');
            }

            // Crear el detalle de la cita

            $consulta = Appointment_detail::create([
                'date' => $request->date,
                'appointment_id' => $request->appointment_id,
                'total' => $request->total
            ]);

         //   dd($consulta);

            //cambiar estado de la cita
            $status = Appointment::find($request->appointment_id);

            if ($status) {
                $status->status = 'completo'; 
                $status->updated_at = now(); 
                $status->save(); 
            }

            // Redirigir de nuevo con un mensaje de éxito
            return redirect()->back()->with('correct', 'Registro exitoso!!');
        } catch (ValidationException $err) {
            return redirect()->back()->with('error', 'Hubo un error en el registro: ' . $err->getMessage());
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Hubo un error en el registro: ' . $th->getMessage());
        }
    }

    public function consultation()
    {
        return view('consultation');
    }

    public function showConsultations(Request $request)
    {
        try {
            // Validar las fechas recibidas
            $dates = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            // Extraer las citas entre las fechas seleccionadas
            $consultations = Appointment_detail::whereBetween('date', [$dates['start_date'], $dates['end_date']])
                ->with(['appointment.patient'])
                ->get();


            return response()->json([
                'data' => $consultations,
                'error' => null,
                'message' => 'Citas extraídas con éxito.'
            ], 200);
            
        } catch (ValidationException $err) {
            return response()->json([
                'message' => 'Error en la validación.',
                'error' => $err->getMessage(),
                'data' => null
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ocurrió un error al procesar la solicitud.',
                'error' => $th->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
