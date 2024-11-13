<?php

namespace App\Http\Controllers;

use App\Models\Appointment_detail;
use App\Models\Therapy;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TherapyController extends Controller
{
    public function index()
    {
        Gate::authorize('register-employees');

        $therapies = Therapy::all();
        //dd($therapies);
        return view('therapies.therapies')->with('therapies', $therapies);
    }

    public function edit($id)
    {
        Gate::authorize('register-employees');

        $therapy = Therapy::find($id);

        //dd($therapy);

        return view('therapies.edit')->with('therapy', $therapy);
    }

    public function update(Request $request)
    {
        try {
            //dd($request);
            Gate::authorize('register-employees');
            // Validar datos de entrada
            $validatedData = $request->validate([
                'name' => 'required|string|min:1',
                'price' => 'required|numeric',
                'password' => 'required'
            ]);

            // Verificar la contraseña del usuario autenticado
            if (!Hash::check($validatedData['password'], auth()->user()->password)) {
                session()->flash('error', 'Contraseña incorrecta');
                return redirect()->back();
            }

            // Encontrar la terapia por ID y actualizar
            $therapy = Therapy::findOrFail($request->id);
            $therapy->update([
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
            ]);

            session()->flash('success', 'Terapia actualizada con éxito');
            return redirect()->route('therapies');
        } catch (ValidationException $exception) {
            session()->flash('error', 'Datos inválidos');
            return redirect()->back();
        } catch (Exception $exception) {
            session()->flash('error', 'Ocurrió un error al actualizar la terapia: ' . $exception->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        return view('therapies.add');
    }

    public function store(Request $request)
    {
        try {
            $therapy = $request->validate([
                'name' => 'required|string|min:1',
                'price' => 'required|numeric|min:1',
                'password' => 'required'
            ]);

            if (!Hash::check($therapy['password'], auth()->user()->password)) {
                session()->flash('error', 'Contraseña incorrecta');
                return redirect()->back();
            }

            Therapy::create([
                'name' => $therapy['name'],
                'price' => $therapy['price']
            ]);

            session()->flash('success', 'Terapia agregada con éxito!');
            return redirect()->route('therapies');

        } catch (ValidationException $err) {
            session()->flash('error', 'Datos inválidos');
            return redirect()->back();
        } catch (Exception $exception) {
            session()->flash('error', 'Ocurrió un error al actualizar la terapia: ' . $exception->getMessage());
            return redirect()->back();
        }
    }

    public function discount($id)
    {
        Gate::authorize('register-employees');


        $therapies =  Therapy::find($id);

        //dd($therapies);

        return view('therapies.discount')->with('therapy', $therapies);
    }

    public function discountAdd(Request $request)
    {
        Gate::authorize('register-employees');

        try {
            $therapy = $request->validate([
                "name" => "required|min:1",
                "price" => "required|numeric",
                "discount_amount" => "required|numeric",
                "discount_start" => "required|date",
                "discount_end" => "required|after_or_equal:discount_start",
                "password" => "required",
                "id" => "required"
            ],
        [
            'discount_end.after_or_equal' => 'Fecha de inicio no puede estar despues de fecha final'
        ]);


            if (!Hash::check($therapy['password'], auth()->user()->password)) {
                session()->flash('error', 'Contraseña incorrecta');
                return redirect()->back();
            }

            

            $therapies = Therapy::findOrFail($therapy['id']);
            $therapies->discount_amount = $request->input('discount_amount');
            $therapies->discount_start = $request->input('discount_start');
            $therapies->discount_end = $request->input('discount_end');
            $therapies->save();

            session()->flash('success', 'Descuento actualizado correctamente');
            return redirect()->route('therapies');

        } catch (ValidationException $err) {
            session()->flash('error', 'Datos inválidos: ' . $err->getMessage());
            return redirect()->back();
        } catch (Exception $exception) {
            session()->flash('error', 'Ocurrió un error ingresar el descuento: ' . $exception->getMessage());
            return redirect()->back();
        }
    }

    // public function destroy($id)
    // {
    //     try {
    //         // Cambiar firstOrFail a findOrFail para buscar por ID
    //         $therapy = Therapy::findOrFail($id);
    //         $therapy->delete(); // Asegúrate de eliminar el registro

    //         session()->flash('success', 'Terapia eliminada con éxito');
    //         return redirect()->route('therapies');
    //     } catch (Exception $exception) {
    //         session()->flash('error', 'Ocurrió un error al eliminar la terapia: ' . $exception->getMessage());
    //         return redirect()->back();
    //     }
    // }

}
