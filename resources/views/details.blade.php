<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center sm:text-left">
            {{ __('Detalle de Citas') }}
        </h2>
    </x-slot>

  <!-- cobrar cita -->
    <div class="flex flex-col items-center justify-center py-4 gap-6 m-2">
        <form id="search-date" action="{{ route('details.show') }}" method="GET" class="w-full sm:w-1/3 lg:w-1/4 bg-white p-4 sm:m-2 shadow-md rounded-md">
            <h3 class="mb-4 text-center text-lg font-semibold">Selecciona la fecha de la cita:</h3>
            <input type="date" name="date" id="date" class="w-full text-sm text-black border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer mb-4" required />
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Buscar</button>
        </form>

        <!-- appointments table -->
        <div class="container mx-auto px-4 py-6">
            @if ($appointments ?? null)
            <h3 class="text-2xl font-semibold mb-4 text-center">Listado de Citas</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-xs md:text-sm leading-normal">
                            <th class="py-2 px-2 text-left">Cita</th>
                            <th class="py-2 px-2 text-left">Paciente</th>
                            <th class="py-2 px-2 text-left">Terapeuta</th>
                            <th class="py-2 px-2 text-left">Fecha</th>
                            <th class="py-2 px-2 text-left">Estatus</th>
                            <th class="py-2 px-2 text-left">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($appointments as $appointment)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-2 px-2 text-left whitespace-nowrap">{{ $appointment['id'] }}</td>
                            <td class="py-2 px-2 text-left">{{ $appointment['patient'] }}</td>
                            <td class="py-2 px-2 text-left">{{ $appointment['physiotherapist'] }}</td>
                            <td class="py-2 px-2 text-left">{{ $appointment['date'] }}</td>
                            @if($appointment['status'] === 'pendiente')
                            <td class="py-2 px-2 text-left bg-yellow-400 font-bold text-white">
                                {{ $appointment['status'] }}
                            </td>
                            @elseif($appointment['status'] === 'completo')
                            <td class="py-2 px-2 text-left bg-green-600 text-white">
                                {{ $appointment['status'] }}
                            </td>
                            @else
                            <td class="py-2 px-2 text-left bg-red-600 text-white">
                                {{ $appointment['status'] }}
                            </td>
                            @endif

                            <td class="py-2 px-2 text-left">
                                <form action="{{ route('appointments.show', $appointment['id']) }}" method="GET">
                                    @if($appointment['status'] === 'pendiente')
                                    <button type="submit" class="text-blue-500 hover:underline hover:cursor-pointer">Cobrar</button>
                                    @elseif($appointment['status'] === 'completo')
                                    <button type="submit" class="text-green-500" disabled>Cobrado</button>
                                    @else
                                    <button type="submit" class="text-red-500" disabled>Cancelada</button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-center text-gray-500">No hay citas para mostrar.</p>
            @endif
        </div>
    </div>

</x-app-layout>