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
            $validacion = $request->validate([
                'employee_id' => 'required|exists:users,id',
                'password' => 'required'
            ]);
    
            // Encontrar el usuario
            $user = User::find($validacion['employee_id']);

            if($user->staus === 'inactivo'){
                return redirect()->back()->with('error', 'Usuario no encontrado.');
            }
    
            // Verificar si el usuario está intentando eliminar su propia cuenta
            if ($user->id === auth()->user()->id) {
                return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta.');
            }
    
            // Verificar si el usuario existe y si la contraseña es correcta
            if ($user && Hash::check($validacion['password'], auth()->user()->password)) {
                $user->status = 'inactivo';
                $user->email = '';
                $user->save(); // Guardar los cambios
                
                return redirect()->route('register')
                    ->with('success', 'Usuario eliminado con exito.');
            }
    
            return redirect()->back()->with('error', 'Usuario no encontrado o contraseña incorrecta.');
            
        } catch (ValidationException $err) {
            return redirect()->back()->withErrors($err->validator)->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Ocurrió un error inesperado: ' . $th->getMessage());
        }
    }
    

    
}
