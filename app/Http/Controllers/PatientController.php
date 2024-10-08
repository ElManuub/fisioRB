<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'name' => 'required|string|min:1|max:100',
                'phone_number' => 'required|min:10|max:10',
                'office_id' => 'required'
            ]);


            if (Patient::where('phone_number', $data['phone_number'])->exists()) {

                return response()->json([
                    'message' => 'Paciente ya registrado, por favor ingresar paciente nuevo.',
                    'error' => 'Numero de telefóno ya existe.',
                    'data' => null
                ], 409);
            }

            $patient = Patient::create($data);

            return response()->json([
                'message' => 'Paciente registrado con exito',
                'error' => null,
                'data' => $patient
            ], 200);
        } catch (ValidationException $err) {

            return response()->json([
                'message' => 'Algunos datos son incorrectos, favor de revisar de nuevo su inforación.',
                'error' => $err->validator->errors(),
                'data' => null
            ], 400);
        } catch (Exception $ms) {

            return response()->json([
                'message' => 'Ocurrio un error al intentar conectar con el servidor',
                'error' => $ms->getMessage(),
                'data' => null

            ], 500);
        }
    }

    public function showPatient(Request $request)
    {
        try {

            $data = $request->validate([
                'patient_id' => 'nullable|integer',
                'phone_number' => 'nullable|string|min:10|max:10'
            ]);

            $patient = null;

            if (isset($data['patient_id'])) {

                $patient = Patient::with(['office'])->find($data['patient_id']);

                if ($patient == null) {
                    return response()->json([
                        'data' => $patient,
                        'error' => null,
                        'message' => 'ID del cliente no existe.'
                    ], 400);
                }
            }

            if (isset($data['phone_number'])) {

                $patient = Patient::with(['office'])->where('phone_number', $data['phone_number'])->first();


                if ($patient == null) {
                    return response()->json([
                        'data' => $patient,
                        'error' => null,
                        'message' => 'Numero de telefono no existe.'
                    ], 400);
                }
            }

            if ($patient) {

                return response()->json([
                    'data' => $patient,
                    'error' => null,
                    'message' => 'solicitud proccesada con exito'
                ], 200);
            } else {

                return response()->json([
                    'data' => null,
                    'message' => 'No se ingresó ningun dato, favor de intentar de nuevo',
                    'error' => 'No se ingresó ningun dato.'
                ], 404);
            }
        } catch (ValidationException $err) {

            return response()->json([
                'message' => 'Algunos campos no cumplen con la validación. Por favor revisar datos ingresados.',
                'error' => $err->validator->errors(),
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

    public function edit(Request $request)
{
    try {
        // Validación de los datos
        $datos = $request->validate([
            'patient_id' => 'required', 
            'patient' => 'required|max:100',
            'phone_number' => 'required|min:10|max:10'
        ]);

        // Buscar al paciente
        $patient = Patient::find($datos['patient_id']);

        // Si el paciente no existe, lanzar error
        if (!$patient) {
            return response()->json([
                'message' => 'Paciente no encontrado.',
                'error' => 'El ID del paciente no es válido.',
                'data' => null
            ], 404);
        }

        // Comprobar si el número de teléfono ya existe para otro paciente
        $existingPatient = Patient::where('phone_number', $datos['phone_number'])
            ->where('id', '!=', $patient->id) // Ignorar al paciente actual
            ->first();

        if ($existingPatient) {
            return response()->json([
                'message' => 'El número de teléfono ya está en uso por otro paciente.',
                'error' => 'Número de teléfono duplicado.',
                'data' => null
            ], 409); // 409 Conflict, ya que es un conflicto de datos duplicados
        }

        // Actualizar los datos del paciente
        $patient->name = $datos['patient'];
        $patient->phone_number = $datos['phone_number'];
        $patient->save();

        // Respuesta exitosa
        return response()->json([
            'message' => 'Paciente actualizado con éxito.',
            'error' => null,
            'data' => $patient
        ], 200);
    } catch (ValidationException $err) {
        // Manejo de errores de validación
        return response()->json([
            'message' => 'Algunos datos no cumplen con las reglas de validación.',
            'error' => $err->errors(),
            'data' => null
        ], 422); // Cambié a 422 (Unprocessable Entity) para las validaciones
    } catch (Exception $ms) {
        return response()->json([
            'message' => 'Ocurrió un error al intentar conectar con el servidor',
            'error' => $ms->getMessage(),
            'data' => null
        ], 500);
    }
}

}
