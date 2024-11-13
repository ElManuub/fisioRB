<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Office;
use App\Models\Therapy;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Appointment_detail;
use Illuminate\Support\Facades\DB;
use Dotenv\Exception\ValidationException;

class AppointmentDetailController extends Controller
{

    public $consultation_income;
    public $dates;

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

        $therapies = Therapy::all();

        return view('appointment_detail')->with(['patient' => $details, 'therapies' => $therapies]);
    }

    public function total(Request $request)
    {
        //dd($request);

        try {
            $request->validate(
                [
                    'date' => 'required|date',
                    'appointment_id' => 'required|integer',
                    'total' => 'required|numeric|min:1',
                    'therapies' => 'nullable|array',
                    'therapies.*' => 'integer|exists:therapies,id',
                    'therapies_prices' => 'nullable|array',
                    'therapies_prices.*' => 'numeric|min:0',
                    'physiotherapist' => 'required|string',
                    'query_type' => 'required|numeric|min:0'
                ],
                [
                    'total.min' => 'No puedes dejar vacío el campo total.',
                    'query_type' => 'Tienes que especificar el tipo decita'
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
                'extra' => $request->extra,
                'appointment_id' => $request->appointment_id,
                'total' => $request->total,
                'query_type' => $request->query_type
            ]);

            // Registrar terapias elegidas en la tabla intermedia
        if($request->has('therapies')){
            foreach($request->therapies as $therapyId){
                DB::table('appointment_detail_therapy')->insert([
                    'appointment_detail_id' => $consulta->id,
                    'therapy_id' => $therapyId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

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
        // Encuentra la oficina a la que pertenece el usuario autenticado
        $office = Office::find(auth()->user()->office_id);

        // Encuentra todos los terapeutas en la misma oficina
        $therapists = User::where('office_id', $office->id)->get();

        // Retorna la vista con la lista de terapeutas
        return view('consultation')->with('therapists', $therapists);
    }

    public function showConsultations(Request $request)
    {
        try {
            // Validar las fechas recibidas
            $this->dates = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            // Extraer las citas entre las fechas seleccionadas
            $this->consultation_income = Appointment_detail::whereBetween('date', [$this->dates['start_date'], $this->dates['end_date']])
                ->with(['appointment.patient', 'appointment.user', 'appointment.user.office'])
                ->get();

            // Guardar en la sesión
            session(['consultation_income' => $this->consultation_income]);
            session(['dates' => $this->dates]);


            return response()->json([
                'data' => $this->consultation_income,
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

    public function imprimir()
{
    $this->consultation_income = session('consultation_income', collect()); // Colección vacía si no existe
    $this->dates = session('dates', collect());

    //dd($this->consultation_income);

    $pdf = Pdf::loadView('exports_tables.pdf', ['incomes' => $this->consultation_income, 'dates' => $this->dates]);
    return $pdf->stream('Ingresos_'. now()->format('Y-m-d') . '.pdf');
}
}
