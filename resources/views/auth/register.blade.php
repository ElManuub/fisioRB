<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center sm:text-left">
            {{ __('Registro de nuevos usuarios') }}
        </h2>
    </x-slot>
    <div class="w-full mx-auto sm:max-w-md mt-6 mb-4 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nombre')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Correo')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div><br>

            <!-- Office -->
            <div class="mt-2">
                <select class='w-full border-gray-300 block focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm' name='office_id'>
                <option value="">Selecciona Sucursal</option>    
                @foreach ($offices as $office)
                <option value="{{ $office['id'] }}">{{ $office['name']}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tipo de usuario -->
            <div class="mt-4">
                <select class='w-full border-gray-300 block mt-1 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm' name='role'>
                    <option value="">Selecciona el tipo de usuario a registrar</option>
                    <option value="1">Administrador</option>
                    <option value="2">Terapeuta</option>
                </select>

            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                    type="password"
                    name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ms-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>