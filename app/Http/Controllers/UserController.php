<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public function deleteUser(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validacion = $request->validate(
                [
                    'employee_id' => 'required|exists:users,id',
                    'password' => 'required'
                ],
                [
                    'employee_id.required' => 'El ID del usuario es obligatorio.',
                    'employee_id.exists' => 'El usuario con el ID proporcionado no existe.',
                ]
            );

            // Encontrar el usuario
            $user = User::find($validacion['employee_id']);

            // Si no se encuentra el usuario, devolver un error específico
            if (!$user) {
                return redirect()->back()->with('error', 'El usuario con el ID proporcionado no existe.');
            }

            // Verificar si el usuario está inactivo
            if ($user->status === 'inactivo') {
                return redirect()->back()->with('error', 'El usuario está inactivo y no puede ser encontrado.');
            }

            // Verificar si el usuario está intentando eliminar su propia cuenta
            if ($user->id === auth()->user()->id) {
                return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta.');
            }

            // Verificar si la contraseña del usuario autenticado es correcta
            if (Hash::check($validacion['password'], auth()->user()->password)) {
                $user->status = 'inactivo';
                $user->save(); // Guardar los cambios

                return redirect()->route('register')
                    ->with('success', 'Usuario eliminado con éxito.');
            }

            return redirect()->back()->with('error', 'Contraseña incorrecta.');
        } catch (ValidationException $err) {
            return redirect()->back()->withErrors($err->validator)->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Ocurrió un error inesperado: ' . $th->getMessage());
        }
    }
}
