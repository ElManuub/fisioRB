<x-app-layout>
    @vite(['resources/js/calendar/modal-register.js','resources/js/calendar/calendar.js','resources/css/app.css'])
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center sm:text-left">
            {{ __('Calendario') }}
        </h2>
    </x-slot>

    <!-- calendar -->
    <div id="calendar" class="w-full h-full sm:w-11/12 lg:w-3/4 mx-auto p-4 sm:p-6 lg:p-8 bg-white rounded-lg shadow mt-6">
    </div>

    <!-- search patient -->
    <div class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="modal-search">
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto bg-gray-500 bg-opacity-75">
            <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full max-w-md sm:max-w-lg">
                    <div class="bg-white p-6 sm:p-8">
                        <div class="flex justify-end mb-4">
                            <img src="/images-iconos/cruzar.png" alt="Cerrar" width="25px" id="close-modal" class="hover:cursor-pointer hover:bg-gray-200 rounded p-1">
                        </div>

                        <!-- Search client by -->
                        <div id="client-search" class="grid gap-4 mt-4 grid-cols-1 sm:grid-cols-1 pb-4">
                            <h3 class="text-lg font-semibold leading-6 text-center col-span-2" id="modal-title">¿El cliente está registrado?</h3>
                            <div class="col-span-2 sm:col-span-1">
                                <select class="client-register w-full p-2 border border-gray-300 rounded-lg">
                                    <option selected>Selecciona</option>
                                    <option value="yes">Sí</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search client -->
                        <form action="" id="client-exist" class="hidden">
                            @csrf
                            <h3 class="text-base font-semibold leading-6 text-center mt-4">Buscar por:</h3>
                            <div class="grid gap-4 mt-4 grid-cols-1 sm:grid-cols-1 pb-4">
                                <div class="col-span-2 sm:col-span-1">
                                    <label for="search-id" class="block text-sm font-medium text-gray-700">Número de cliente:</label>
                                    <input type="number" min="0" name="patient_id" id="search_id" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" placeholder="ID de cliente" required>

                                    <label for="search_number" class="block mt-4 text-sm font-medium text-gray-700">Número de teléfono:</label>
                                    <input type="tel" name="phone_number" id="search_number" min="10" max="10" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" placeholder="Número de teléfono" required>
                                </div>
                            </div>
                            <div class="px-4 py-3 sm:px-6 sm:py-4 mt-6 sm:flex justify-center">
                                <button type="button" id="btn-search" class="w-full sm:w-auto inline-flex justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Buscar</button>
                            </div>
                        </form>

                        <!-- Register -->
                        <form action="" id="client-new-register" class="hidden">
                            @csrf
                            <h3 class="text-base font-semibold leading-6 text-center mt-4">Nuevo registro:</h3>
                            <div class="grid gap-4 mt-4 grid-cols-1 sm:grid-cols-1 pb-4">
                                <div class="col-span-2 sm:col-span-1">
                                    <label for="new-name" class="block text-sm font-medium text-gray-700">Nombre:</label>
                                    <input type="text" id="name-register" name="name" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" placeholder="Nombre" required>

                                    <label for="register-phone" class="block mt-4 text-sm font-medium text-gray-700">Número de teléfono:</label>
                                    <input type="text" name="phone_number" id="register-phone" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" placeholder="Número de teléfono" required>
                                    <input type="hidden" id="phone-register" name="office_id" value="{{auth()->user()->office_id}}">
                                </div>
                            </div>
                            <div class="px-4 py-3 sm:px-6 sm:py-4 mt-6 sm:flex justify-center">
                                <button type="button" id="btn-register" class="w-full sm:w-auto inline-flex justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Registrar</button>
                            </div>
                        </form>

                        <!-- Appointment -->
                        <form action="" id="client-new-appointment" class="hidden">
                            @csrf
                            <h3 class="text-base font-semibold leading-6 text-center mt-4">Nueva Cita:</h3>
                            <div class="grid gap-4 mt-4 grid-cols-1 sm:grid-cols-1 pb-4">
                                <div class="col-span-2 sm:col-span-1">
                                    <label for="client_id" class="block text-sm font-medium text-gray-700">ID:</label>
                                    <input type="text" name="patient_id" id="client_id" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" readonly>

                                    <label for="new-name" class="block mt-4 text-sm font-medium text-gray-700">Paciente:</label>
                                    <input type="text" name="name" id="new-name" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" readonly>

                                    <label for="new-phone" class="block mt-4 text-sm font-medium text-gray-700">Número de teléfono:</label>
                                    <input type="text" name="phone_number" id="new-phone" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" readonly>

                                    <label for="appointment-date" class="block mt-4 text-sm font-medium text-gray-700">Fecha:</label>
                                    <input type="date" name="date" id="appointment-date" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" required>

                                    <label for="start" class="block mt-4 text-sm font-medium text-gray-700">Hora de inicio:</label>
                                    <input type="time" name="start_time" id="start" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" required>

                                    <label for="end" class="block mt-4 text-sm font-medium text-gray-700">Hora de salida:</label>
                                    <input type="time" name="end_time" id="end" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" required>


                                    <label for="note" class="block mt-4 text-sm font-medium text-gray-700">Nota:</label>
                                    <textarea name="note" id="note" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" required></textarea>

                                    <input name="user_id" value="{{ auth()->user()->id}}" hidden>
                                </div>
                            </div>
                            <div class="px-4 py-3 sm:px-6 sm:py-4 mt-6 sm:flex justify-center">
                                <button type="button" id="btn-appointment" class="w-full sm:w-auto inline-flex justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Registrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- edit patient -->
    <div class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="modal-edit">
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto bg-gray-500 bg-opacity-75">
            <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full max-w-md sm:max-w-lg">
                    <div class="flex justify-end pt-4 pr-4">
                        <img src="/images-iconos/cruzar.png" alt="Cerrar" width="30px" id="close-modal-edit" class="hover:cursor-pointer hover:bg-gray-200 rounded p-1">
                    </div>
                    <form action="" id="client-edit-appointment-modal" class="p-6">
                        @csrf
                        <h3 class="text-base font-semibold leading-6 text-center">Cita</h3>
                        <div class="grid gap-4 mt-4">
                            <div>
                                <!-- id patient -->
                                <input style="background-color:lightgrey;" type="text" name="patient_id" id="client_id_edit" class="hidden" readonly>

                                <label for="patient_edit" class="block text-sm font-medium text-gray-700 mt-4">Paciente:</label>
                                <input style="background-color:lightgrey;" type="text" name="patient" id="patient_edit" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" readonly>

                                <label for="phone_edit" class="block text-sm font-medium text-gray-700 mt-4">Número de teléfono:</label>
                                <input style="background-color:lightgrey;" type="text" name="phone_number" min="10" max="10" id="phone_edit" class="w-full mt-1 p-2 border border-gray-300 rounded-lg" readonly>

                                <label for="user_id" class="block text-sm font-medium text-gray-700 mt-4">ID Terapeuta:</label>
                                <input type="text" name="user_id" id="user_id" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">

                                <label for="appointment-date-edit" class="block text-sm font-medium text-gray-700 mt-4">Fecha:</label>
                                <input type="date" name="date" id="appointment-date-edit" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">

                                <label for="start-edit" class="block text-sm font-medium text-gray-700 mt-4">Hora de inicio:</label>
                                <input type="time" name="start_time" min="09:30" max="19:00" id="start-edit" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">

                                <label for="end-edit" class="block text-sm font-medium text-gray-700 mt-4">Hora de salida:</label>
                                <input type="time" name="end_time" min="09:30" max="19:00" id="end-edit" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">

                                <label for="note-edit" class="block text-sm font-medium text-gray-700 mt-4">Nota:</label>
                                <textarea name="note" id="note-edit" class="w-full mt-1 p-2 border border-gray-300 rounded-lg"></textarea>

                                <input type="text" name="appointment_id" id="appointment_id" class="hidden">

                            </div>
                        </div>
                        <div class="px-4 py-3 mt-6 sm:flex sm:flex-row-reverse gap-4">
                            <button type="submit" id="btn-update-edit" class="w-full sm:w-auto inline-flex justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm mb-2 hover:bg-sky-700">Actualizar</button>
                            <button type="submit" id="btn-cancel-edit" class="w-full sm:w-auto inline-flex justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm mb-2 hover:bg-red-700">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>