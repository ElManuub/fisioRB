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

    <div class="flex flex-col items-center justify-center pt-4 gap-6">
    <h3 class="mb-2 text-center text-lg font-semibold">Terapias</h3>

    <table>
      <thead>
        <tr class="bg-gray-200 text-gray-600 uppercase text-xs md:text-sm leading-normal">
        <th class="py-2 px-2 text-left">ID</th>
        <th class="py-2 px-2 text-left">Nombre</th>
        <th class="py-2 px-2 text-left">Precio</th>
        <th class="py-2 px-2 text-left">Editar</th>
        <th class="py-2 px-2 text-left">Eliminar</th>
        </tr>
      </thead>
      <tbody class="text-gray-600 text-sm font-light">
      @if ($therapies !== null)
        @foreach ($therapies as $therapy)
        <tr class="border-b border-gray-200 hover:bg-gray-100">
        <td class="py-2 px-2 text-left">{{$therapy->id}}</td>
        <td class="py-2 px-2 text-left">{{$therapy->name}}</td>
        <td class="py-2 px-2 text-left">{{$therapy->price}}</td>
        <td class="py-2 px-2 "><img src="{{ asset('images-iconos/boton-editar.png') }}" alt="editar" width="25px"></td>
        <td class="py-2 px-2 text-center"><img src="{{ asset('images-iconos/basura.png') }}" alt="eliminar" width="25px"></td>

        </tr>
        @endforeach
      @else
      <h2>No hay datos para mostrar</h2>  
      @endif
      </tbody>
    </table>
    </div>
</x-app-layout>