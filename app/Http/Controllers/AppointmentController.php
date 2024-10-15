<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Exceptions\Exception;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
  public function store(Request $request)
  {

    try {
      /*
      $start_time = date('H:i', strtotime($request->input('start_time')));
      $end_time = date('H:i', strtotime($request->input('end_time')));

      // Actualizar el request con los horarios formateados
      $request->merge([
        'start_time' => $start_time,
        'end_time' => $end_time
      ]);

      // Definir el rango de horas permitido
      $min_time = '09:30';
      $max_time = '18:00';

      // Verificar si las horas están dentro del rango permitido
      if ($start_time < $min_time || $end_time > $max_time) {
        return response()->json([
          'error' => 'Error en asignación de horarios',
          'data' => null,
          'message' => 'El horario debe estar entre 09:30 y 18:00.'
        ], 400);
      }*/

      $data = $request->validate([
        'date' => 'date|required|date_format:Y-m-d',
        'note' => 'string|nullable|max:255',
        'start_time' => 'string|required|date_format:H:i',
        'end_time' => 'string|required|date_format:H:i|after:start_time',
        'user_id' => 'integer|required',
        'patient_id' => 'integer|required'
      ]);

      Appointment::create($data);

      return response()->json([
        'data' => $data,
        'error' => null,
        'message' => 'solicitud proccesada con exito'
      ], 200);
    } catch (ValidationException $error) {

      return response()->json([
        'error' => $error->validator->errors(),
        'data' => null,
        'message' => 'Algunos datos pueden que esten incorrectos, favor de revisar bien su informacion.'
      ], 400);
    } catch (\Throwable $error) {

      return response()->json([
        'error' => $error->getMessage(),
        'data' => null,
        'message' => 'Error al insertar datos'
      ], 500);
    }
  }

  public function show()
{
    $citas = Appointment::with('patient', 'user')->get()->map(function ($cita) {
        
      // Definir color según el estado de la cita
        $color = '';
        switch ($cita->status) {
            case 'pendiente':
                $color = '#f1c40f'; 
                break;
            case 'completo':
                $color = '#2ecc71'; 
                break;
            case 'cancelado':
                $color = '#e74c3c'; 
                break;
        }

        return [
            'id' => $cita->id,
            'date' => $cita->date,
            'note' => $cita->note,
            'start_time' => $cita->start_time,
            'end_time' => $cita->end_time,
            'user_id' => $cita->user->name,
            'patient_id' => $cita->patient->name,
            'status' => $cita->status, 
            'color' => $color 
        ];
    });

    return response()->json($citas, 200);
}

  public function consultAppointment(Request $request)
  {
      try {
          $citas = $request->validate([
              'type' => 'nullable|integer',
              'consultation_date_inicio' => 'required|date',
              'consultation_date_fin' => 'required|date|after_or_equal:consultation_date_inicio'
          ]);
  
          // Construir la consulta de citas
          $query = Appointment::query();
  
          // Filtrar por tipo si se proporciona
          if ($request->filled('type')) {
              $query->where('user_id', $request->type);
          }
  
          // Filtrar por rango de fechas
          $query->whereBetween('date', [
              $request->consultation_date_inicio, 
              $request->consultation_date_fin
          ]);
  
          // Incluir la relación con 'patient' y 'user'
          $citas = $query->with('patient', 'user')->get()->map(function ($cita) {
              return [
                  'id' => $cita->id,
                  'date' => $cita->date,
                  'note' => $cita->note,
                  'start_time' => $cita->start_time,
                  'end_time' => $cita->end_time,
                  'user_id' => $cita->user->name,
                  'patient_id' => $cita->patient->name,
                  'status' => $cita->status
              ];
          });
  
          return response()->json([
            'data' => $citas,
            'error' => null,
            'message' => 'Citas encontradas con exito'
        ], 200);

      } catch (ValidationException $th) {
        return response()->json([
            'data' => null,
            'error' => 'Error al consultar las citas.',
            'message' => $th->validator->errors()
        ], 400);
    } catch (\Throwable $th) {
          return response()->json([
              'data' => null,
              'error' => 'Error al consultar las citas.',
              'message' => $th->getMessage(),
          ], 500);
      }
  }
  
  public function edit($id)
  {
    try {
      $data = Appointment::find($id);

      $data->load(['patient.office', 'user']);


      $patient = [
        "appointment_id" => $data->id,
        "date" => $data->date,
        "note" => $data->note,
        "start_time" => $data->start_time,
        "end_time" => $data->end_time,
        "patient" => $data->patient->name,
        "id" => $data->patient_id,
        "phone_number" => $data->patient->phone_number,
        "user_id" => $data->user_id
      ];

      return response()->json([
        'data' => $patient,
        'error' => null,
        'message' => 'solicitud proccesada con exito'
      ], 200);

    } catch (\Throwable $th) {

      return response()->json([
        'data' => null,
        'error' => $th->getMessage(),
        'message' => 'Hubo un error! por favor intentar de nuevo.'
      ], 500);
    }

  }

  public function update(Request $request, $id)
  {
      try {
          $start_time = date('H:i', strtotime($request->input('start_time')));
          $end_time = date('H:i', strtotime($request->input('end_time')));
  
          // Actualizar el request con los horarios formateados
          $request->merge([
              'start_time' => $start_time,
              'end_time' => $end_time
          ]);
  
          // Definir el rango de horas permitido
          $min_time = '09:00';
          $max_time = '22:00';
  
          // Verificar si las horas están dentro del rango permitido
          if ($start_time < $min_time || $end_time > $max_time) {
              return response()->json([
                  'error' => 'Error en asignación de horarios',
                  'data' => null,
                  'message' => 'El horario debe estar entre 09:00 am y 10:00 pm.'
              ], 400);
          }
  
          $data = $request->validate([
              'phone_number' => 'required|max:10',
              'date' => 'required|date',
              'note' => 'nullable|string|max:255',
              'start_time' => 'required|string|date_format:H:i',
              'end_time' => 'required|string|date_format:H:i|after:start_time',
              'patient_id' => 'required|integer',
              'user_id' => 'required|integer'
          ]);
  
          // Encontrar la cita por ID
          $appointment = Appointment::find($id);
  
          if ($appointment === null) {
              return response()->json([
                  'message' => 'Cita no encontrada',
                  'data' => null,
                  'error' => 'ID de cita no existe'
              ], 404);
          }
  
          if ($appointment->status === 'completo') {
              return response()->json([
                  'data' => null,
                  'error' => 'no puedes editar citas en status completo.',
                  'message' => 'No puedes editar una cita con estatus "Completo".'
              ], 500);
          }
  
          // Verificar si el usuario existe y está activo
          $user = User::find($request->user_id);
  
          if (!$user || $user->status !== 'activo') {
              return response()->json([
                  'message' => 'ID de Terapeuta no existe o no está activo, favor de revisar',
                  'data' => null,
                  'error' => 'user_id no válido o inactivo'
              ], 404);
          }
  
          // Actualizar la cita con los datos validados
          $appointment->update($request->all());
  
          return response()->json([
              'message' => 'Actualización realizada con éxito',
              'data' => $appointment,
              'error' => null
          ], 200);
  
      } catch (ValidationException $error) {
          return response()->json([
              'error' => $error->validator->errors(),
              'data' => null,
              'message' => 'Algunos datos no cumplen con los requerimientos de validación. Por favor, revisa tu información.'
          ], 400);
      } catch (\Throwable $error) {
          return response()->json([
              'error' => $error->getMessage(),
              'data' => null,
              'message' => 'Error al actualizar datos'
          ], 500);
      }
  }
  

  public function destroy($id)
  {
      $appointment = Appointment::find($id);
  
      if (!$appointment) {
          return response()->json([
              'data' => null,
              'error' => 'No se encontró la cita.',
              'message' => 'La cita no existe.'
          ], 404);
      }
  
      if ($appointment->status == 'completo') {
          return response()->json([
              'data' => $appointment,
              'error' => 'Una cita que ya ha sido completada no puede ser cancelada.',
              'message' => 'No se puede eliminar una cita que ya ha sido atendida.'
          ], 400);
      }
  
      if ($appointment->status == 'pendiente') {
          $appointment->status = 'cancelado';
          $appointment->save();
  
          return response()->json([
              'data' => $appointment,
              'error' => null,
              'message' => 'Cita eliminada con éxito.'
          ], 200);
      }

      if ($appointment->status == 'cancelado') {

        return response()->json([
            'data' => $appointment,
            'error' => 'Cita ya ha sido eliminada.',
            'message' => 'No se puede Cancelar una cita 2 veces.'
        ], 400);
    }
  
      return response()->json([
          'data' => $appointment,
          'error' => 'Estado no válido.',
          'message' => 'Hubo un problema al procesar la solicitud.'
      ], 400);
  }
  
}
