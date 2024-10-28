<x-app-layout>
  <x-slot name="header" class="bg-black-500">
    <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
      {{ __('Usuarios') }}
    </x-nav-link>
    <x-nav-link :href="route('therapies')" :active="request()->routeIs('therapies')">
      {{ __('Terapias') }}
    </x-nav-link>
    <x-nav-link :href="route('consultation.appointments')" :active="request()->routeIs('consultation.appointments')">
      {{ __('Sucursales') }}
    </x-nav-link>
  </x-slot>

  <div class="flex flex-col items-center justify-center pt-4 gap-6 w-full lg:px-12">
  <h3 class="mb-2 text-center text-lg font-semibold text-gray-700">Agregar nueva terapia</h3>

  @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
      <strong class="font-bold">¡Éxito!</strong>
      <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
      <strong class="font-bold">Error:</strong>
      <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

  <div class="w-full max-w-md px-6 py-4 bg-white rounded-lg shadow-md">
    <form action="{{ route('therapy.store') }}" method="POST" class="space-y-4">
      @csrf
      <div class="flex flex-col">
        <label for="name_therapy" class="text-gray-600 font-medium">Nombre:</label>
        <input 
          type="text" 
          id="name_therapy" 
          name="name" 
          class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
          required>
      </div>

      <div class="flex flex-col">
        <label for="price" class="text-gray-600 font-medium">Precio:</label>
        <input 
          type="text" 
          id="price" 
          name="price" 
          class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
          required>
      </div>

      <div class="flex flex-col">
        <label for="password" class="text-gray-600 font-medium">Por favor ingresa tu contraseña para confirmar cambio:</label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
          required>
      </div>

      <input type="text" name="id" class="hidden">

      <div class="flex justify-center">
        <x-primary-button>Agregar</x-primary-button>
      </div>
    </form>
  </div>
</div>



</x-app-layout>