<x-app-layout>
  @vite(['resources/js/consultation/consultation.js'])
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center sm:text-left">
      {{ __('Consultas') }}
    </h2>
  </x-slot>

  <!-- Busqueda -->
  <div class="flex flex-col items-center justify-center pt-4 gap-6">
    <h3 class="mb-2 text-center text-lg font-semibold">Tipo de consulta:</h3>
    <select id="select-type" name="type" class="lg:w-1/2 sm:w-full md:w-1/2 text-sm text-black bg-white border border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer rounded-md" required>
      <option value="" selected disabled>Selecciona</option>
      <option value="clientes">Clientes</option>
      <option value="consultas">Consultas</option>
      <option value="citas">Citas</option>
      <option value="ingresos">Ingresos</option>
    </select>
  </div>

  <!-- Formulario para Clientes -->
  <div id="client-div" class="flex flex-col items-center justify-center py-2 gap-6 hidden">
    <h3 class="text-base font-semibold leading-6 text-center mt-4">Buscar por:</h3>
    <div class="grid gap-4 mt-4 grid-cols-1 border p-4 w-full sm:w-full lg:w-1/2 bg-white">
      <form action="" id="form-client">
        @csrf
        <label for="client_id" class="block text-sm font-medium text-gray-700">Número de cliente:</label>
        <input type="number" min="0" name="patient_id" id="client_id" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" placeholder="ID de cliente" required>

        <label for="client_phone" class="block mt-4 text-sm font-medium text-gray-700">Número de teléfono:</label>
        <input type="tel" name="phone_number" id="client_phone" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" placeholder="Número de teléfono" required>
      </form>
      <button type="submit" id="search-client" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Buscar</button>
    </div>
  </div>

  <!-- Cliente -->
  <div id="client-div-edit" class="flex flex-col items-center justify-center py-2 gap-6 hidden">
    <h3 class="text-base font-semibold leading-6 text-center mt-4">Datos del paciente:</h3>
    <div class="grid gap-4 mt-4 grid-cols-1 border p-4 w-full sm:w-full lg:w-1/2 bg-white">
      <form action="" id="form-client-edit">
        @csrf
        <label for="patient_id" class="block text-sm font-medium text-gray-700 ">Número de cliente:</label>
        <input type="number" min="0" name="patient_id" id="patient_id" class="w-full mt-1 p-2 border border-gray-300 bg-gray-200 rounded-lg" readonly>

        <label for="patient" class="block text-sm font-medium text-gray-700">Nombre:</label>
        <input type="text" min="0" name="patient" id="patient" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">

        <label for="phone_number" class="block mt-4 text-sm font-medium text-gray-700">Número de teléfono:</label>
        <input type="tel" name="phone_number" id="phone_number" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">

        <label for="officer_id" class="block text-sm font-medium text-gray-700">Numero sucursal:</label>
        <input type="number" min="0" name="officer_id" id="officer_id" class="w-full mt-1 p-2 border border-gray-300 bg-gray-200 rounded" disabled>

        <label for="officer" class="block text-sm font-medium text-gray-700">Nombre sucursal:</label>
        <input type="text" name="officer" id="officer" class="w-full mt-1 p-2 border border-gray-300 bg-gray-200" rounded disabled>
      </form>
      <button type="submit" id="search-client-edit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Editar</button>
    </div>
  </div>

  <!-- Formulario para Consultas -->
  <div id="consultation-form" class="flex flex-col items-center justify-center py-4 gap-6 hidden">
    <h3 class="text-base font-semibold leading-6 text-center mt-4">Buscar por cliente:</h3>
    <div class="grid gap-4 mt-4 grid-cols-1 border p-4 w-full sm:w-full lg:w-1/2 bg-white">
      <div>
        <label for="consultation_client_id" class="block text-sm font-medium text-gray-700">ID de cliente:</label>
        <input type="number" min="0" name="consultation_client_id" id="consultation_client_id" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" placeholder="ID de cliente" required>

        <label for="consultation_phone" class="block mt-4 text-sm font-medium text-gray-700">Número de teléfono:</label>
        <input type="tel" name="consultation_phone" id="consultation_phone" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" placeholder="Número de teléfono" required>

        <h3 class="text-base font-semibold leading-6 text-center mt-4">Buscar por Fecha:</h3>
        <label for="consultation_date" class="block mt-4 text-sm font-medium text-gray-700">Fecha:</label>
        <input type="date" name="consultation_date" id="consultation_date" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" required>
      </div>
      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Buscar</button>
    </div>
  </div>

  <!-- Formulario para citas -->
  <div id="appointment-div" class="flex flex-col items-center justify-center py-4 gap-6 hidden">
    <h3 class="text-base font-semibold leading-6 text-center mt-4">consultar citas por:</h3>
    <div class="grid gap-4 mt-4 grid-cols-1 border p-4 w-full sm:w-full lg:w-1/2 bg-white rounded-md">
      <form id="appointment-form">
        @csrf
        <select id="select-type" name="type" class="lg:w-1/2 sm:w-full text-sm text-black bg-white border border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer rounded-md" required>
          <option value="" selected disabled>Selecciona</option>
          <option value="">General</option>
          <option value="1">Manuel</option>
          <option value="2">David</option>
        </select>
        <label for="appointment_date_inicio" class="block mt-4 text-sm font-medium text-gray-700">Fecha inicio:</label>
        <input type="date" name="consultation_date_inicio" id="appointment_date_inicio" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" required>
        <label for="appointment_date_fin" class="block mt-4 text-sm font-medium text-gray-700">Fecha fin:</label>
        <input type="date" name="consultation_date_fin" id="appointment_date_fin" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" required>
        <br><br>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Buscar</button>
      </form>
    </div>
  </div>

  <!-- consulta de las citas -->
  <div id="appointment-div-consult" class="flex flex-col items-center justify-center py-4 gap-6 hidden">
    <div class="grid gap-4 mt-4 grid-cols-1 border p-4 w-full sm:w-full lg:w-1/2 bg-white rounded-md">
      <table id="appointments-table" class="min-w-full border-collapse border border-gray-300">
        <thead>
          <tr class="bg-gray-200">
            <th class="border border-gray-300 p-2">num</th>
            <th class="border border-gray-300 p-2">Terapeuta</th>
            <th class="border border-gray-300 p-2">paciente</th>
            <th class="border border-gray-300 p-2">Fecha</th>
            <th class="border border-gray-300 p-2">Estado</th>
          </tr>
        </thead>
        <tbody id="appointments-info">

        </tbody>
      </table>
    </div>
  </div>

  <!-- Formulario para Ingresos -->
  <div id="income-form" class="flex flex-col items-center justify-center py-4 gap-6 hidden">
    <h3 class="text-base font-semibold leading-6 text-center mt-4">Selecciona la fecha:</h3>
    <div class="grid gap-4 mt-4 grid-cols-1 border p-4 w-full sm:w-full lg:w-1/2 bg-white">
      <form action="" id="search-income-form">
        @csrf
        <div>
          <label for="start_date" class="block mt-4 text-sm font-medium text-gray-700">Fecha inicio:</label>
          <input type="date" name="start_date" id="start_date" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" required>

          <label for="end_date" class="block mt-4 text-sm font-medium text-gray-700">Fecha final:</label>
          <input type="date" name="end_date" id="end_date" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" required>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 mt-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Buscar</button>
    </div>
    </form>
  </div>

  <!-- consultas para ingresos -->
  <div id="income-form-div" class="flex flex-col items-center justify-center py-2 gap-6 hidden">
    <h3 class="text-base font-semibold leading-6 text-center mt-4">Ingresos:</h3>
    <div class="grid gap-4 mt-4 grid-cols-1 border rounded-md p-4 w-full sm:w-full lg:w-1/2 bg-white">
      <table id="income-table" class="min-w-full border-collapse border border-gray-300">
        <thead>
          <tr class="bg-gray-200">
            <th class="border border-gray-300 p-2">Num</th>
            <th class="border border-gray-300 p-2">Nombre</th>
            <th class="border border-gray-300 p-2">Fecha</th>
            <th class="border border-gray-300 p-2">Total</th>
          </tr>
        </thead>
        <tbody id="income-info">

        </tbody>
      </table>
    </div>
  </div>


</x-app-layout>