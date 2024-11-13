<x-app-layout>
  @vite('resources/js/tiket/tiket.js')
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center sm:text-left">
      {{ __('Detalle de Citas') }}
    </h2>
  </x-slot>

  <div class="w-full max-w-screen-lg mx-auto flex justify-center p-2">

    <!-- Mensaje de error -->

    @if (session('error'))
    <div class="bg-red-100 border border-red-400 mt-2 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
      <strong class="font-bold">Error: </strong>
      <span class="block sm:inline lg:inline">{{ session('error') }}</span>
      <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.parentElement.style.display='none';">
        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <title>Cerrar</title>
          <path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.914l-2.934 2.935a1 1 0 01-1.414-1.414L8.586 10.5 5.652 7.566a1 1 0 111.414-1.414L10 9.086l2.934-2.935a1 1 0 011.414 1.414L11.414 10.5l2.934 2.935a1 1 0 010 1.414z" />
        </svg>
      </span>
    </div>
  </div>
  @endif

  <!-- Mensaje de éxito -->
  @if (session('correct'))
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold">Éxito: </strong>
    <span class="block sm:inline">{{ session('correct') }}</span>
    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.parentElement.style.display='none'; window.location.href='http://127.0.0.1:8000/details';">
      <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
        <title>Cerrar</title>
        <path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.914l-2.934 2.935a1 1 0 01-1.414-1.414L8.586 10.5 5.652 7.566a1 1 0 111.414-1.414L10 9.086l2.934-2.935a1 1 0 011.414 1.414L11.414 10.5l2.934 2.935a1 1 0 010 1.414z" />
      </svg>
    </span>
  </div>


  @elseif ($patient ?? null)
  <form class="w-full sm:w-1/2 lg:w-1/2 bg-white rounded-md p-4 sm:m-4 shadow-md mx-auto" id="appointment" action="{{ route('appointment.total')}}" method="post">
    @method('POST')
    @csrf
    <!-- Paciente -->
    <div class="relative z-0 w-full mb-5 group">
      <input type="text" name="patient" value="{{ $patient['patient'] }}" id="floating_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required readonly />
      <label for="floating_name" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
        Paciente
      </label>
    </div>

    <!-- Teléfono -->
    <div class="relative z-0 w-full mb-5 group">
      <input type="tel" name="phone_number" id="floating_phone" value="{{ $patient['phone_number'] }}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required readonly />
      <label for="floating_phone" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
        Teléfono
      </label>
    </div>

    <!-- Terapeuta -->
    <div class="relative z-0 w-full mb-5 group">
      <input type="text" name="physiotherapist" value="{{ $patient['physiotherapist'] }}" id="floating_physiotherapist" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required readonly />
      <label for="floating_physiotherapist" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
        Terapeuta
      </label>
    </div>

    <!-- fecha -->
    <div class="relative z-0 w-full mb-5 group">
      <input type="date" name="date" value="{{ date('Y-m-d') }}" id="date" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required readonly />
      <label for="date" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
        Fecha de la consulta
      </label>
    </div>

    <!-- Primer Consulta Select -->
    <div class="relative z-0 w-full mb-5 group">
      <label for="first_pay" class="block text-sm text-gray-500 peer-focus:text-blue-600">
        Es primer Consulta
      </label>
      <select name="query_type" id="first_pay" class="block py-2.5 px-0 w-full text-sm text-gray-900 border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" required>

        <option value="0">selecciona</option>
        <option value="800">Si</option>
        <option value="500">No</option>
      </select>
    </div>

    <!-- Tipo de Tratamiento Checkboxes -->
    <div class="relative z-0 w-full mb-5 group">
      <label class="block text-sm text-gray-500 peer-focus:text-blue-600">
        Tipo de tratamiento:
      </label>
      <div class="bg-white p-4 border border-gray-300 rounded-md mt-2">
        @foreach($therapies as $therapy)
        <div class="flex items-center">
          <input id="{{ strtolower(str_replace(' ', '_', $therapy->name)) }}"
            type="checkbox"
            value="{{ $therapy->id }}"
            name="therapies[]"
            data-price="{{ $therapy->price }}" 
          class="text-blue-600 focus:ring-blue-600" />
          <label for="{{ strtolower(str_replace(' ', '_', $therapy->name)) }}"
            class="ml-2 text-sm text-gray-700">
            {{ $therapy->name }} (${{ $therapy->price }})
          </label>
        </div>
        @endforeach
        <input name="appointment_id" value="{{ $patient['appointment_id'] }}" hidden>
      </div>
    </div>

    <!-- Campo de total -->
    <div class="relative z-0 w-full mb-5 group">
      <label class="block text-sm text-gray-500 peer-focus:text-blue-600">
        Total:
      </label>
      <input id="total" type="text" name="total" value="0.00" readonly class="bg-gray-100 border-gray-300 rounded-md p-2">
    </div>

    <!-- Extra -->
    <div class="relative z-0 w-full mb-5 group">
      <label class="block text-sm text-gray-500 peer-focus:text-blue-600">
        Extra:
      </label>
      <input id="extra" type="number" name="extra" step="0.01" min="0" class="border-gray-300 rounded-md p-2">
    </div>

    <!-- Enviar -->
    <div class="relative z-0 w-full mb-5 group">
      <button id="enviar" type="submit" class="bg-blue-500 text-white rounded-md p-2">Enviar</button>
    </div>

  </form>
  @endif
  </div>
</x-app-layout>