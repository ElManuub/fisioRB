<x-app-layout>
    @vite(['resources/js/users/selectUser.js'])
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center sm:text-left">
            {{ __('Registro de nuevos usuarios') }}
        </h2>
    </x-slot>

    <div class="flex flex-col items-center justify-center pt-4 gap-6">
        <h3 class="mb-2 text-center text-lg font-semibold">¿Qué tipo de acción necesitas hacer?</h3>
        <select id="select-type" name="type" class="lg:w-1/2 sm:w-full md:w-1/2 text-sm text-black bg-white border border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer rounded-md" required>
            <option value="" selected disabled>Selecciona</option>
            <option value="users">Usuario Nuevo</option>
            <option value="delete">Eliminar usuario</option>
            <option value="consult">Consultar usuario</option>
        </select>
    </div>

    @if (session('success'))
    <div class="text-green-600 bg-green-100 border border-green-300 rounded-md p-4 max-w-md mx-auto text-center my-4">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="text-red-600 bg-red-100 border border-red-300 rounded-md p-4 max-w-md mx-auto text-center my-4">
        {{ session('error') }}
    </div>
    @endif

    <!-- Mostrar errores de registro, si existen -->
    @if ($errors->any())
    <div class="text-red-600 bg-red-100 border border-red-300 rounded-md p-4 max-w-md mx-auto text-center my-4">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <!-- Form for User Registration -->
    <div class="w-full mx-auto sm:max-w-md mt-6 mb-4 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg hidden" id="register-account">
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
            </div>

            <!-- Office -->
            <div class="mt-2">
                <select class='w-full border-gray-300 block focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm' name='office_id' required>
                    <option value="">Selecciona Sucursal</option>
                    @foreach ($offices as $office)
                    <option value="{{ $office['id'] }}">{{ $office['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tipo de usuario -->
            <div class="mt-4">
                <select class='w-full border-gray-300 block mt-1 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm' name='role' required>
                    <option value="">Selecciona el tipo de usuario a registrar</option>
                    <option value="1">Administrador</option>
                    <option value="2">Terapeuta</option>
                </select>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('¿Ya estás registrado?') }}
                </a>
                <x-primary-button class="ms-4">
                    {{ __('Registrar') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <!-- Form for Deleting User -->
    <div class="mt-4 p-4 sm:p-2 bg-white shadow w-full sm:w-1/2 mx-auto sm:rounded-lg hidden" id="delete-account">
        <div class="max-w-xl w-full">
            <form method="post" action="{{ route('account.deleteUser') }}" class="p-6">
                @csrf
                @method('POST')
                <header>
                    <h2 class="text-lg font-medium text-gray-900">{{ __('Eliminar cuenta de empleado') }}</h2>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Selecciona o ingresa el ID del empleado cuya cuenta deseas eliminar. Esta acción es irreversible.') }}</p>
                </header>

                <!-- Campo para ingresar el ID del empleado -->
                <div class="mt-6">
                    <label for="employee_id" class="block text-sm font-medium text-gray-700">ID de empleado:</label>
                    <input type="text" id="employee_id" name="employee_id" class="w-full mt-1 mb-2 p-2 border border-gray-300 rounded-lg" placeholder="Número de empleado" required>
                </div>

                <!-- Campo para ingresar la contraseña del administrador -->
                <div class="mt-6">
                    <label for="password_admin" class="block text-sm font-medium text-gray-700">Ingresa tu contraseña para confirmar:</label>
                    <input type="password" id="password_admin" name="password" class="w-full mt-1 mb-2 p-2 border border-gray-300 rounded-lg" placeholder="Contraseña" required>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancelar') }}</x-secondary-button>
                    <x-danger-button class="ms-3">{{ __('Dar de baja') }}</x-danger-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Consulta de usuarios -->
    <div class="mt-4 p-4 sm:p-2 w-full mx-auto sm:rounded-lg bg-red hidden" id="consult-account">
        <div class="max-w-xl w-full overflow-x-auto mx-auto">
            @if ($users ?? null)
            <table class="min-w-full border-collapse bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border-b border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-800">ID</th>
                        <th class="border-b border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-800">Nombre</th>
                        <th class="border-b border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-800">Sucursal</th>
                        <th class="border-b border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-800">Estatus</th>
                        <th class="border-b border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-800">Rol</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($users as $user)
                    <tr class="hover:bg-gray-100 transition-colors duration-200">
                        <td class="border-b border-gray-300 px-4 py-2 text-sm text-gray-800">{{ $user->id }}</td>
                        <td class="border-b border-gray-300 px-4 py-2 text-sm text-gray-800">{{ $user->name }}</td>
                        <td class="border-b border-gray-300 px-4 py-2 text-sm text-gray-800">{{ $user->office->name ?? 'N/A' }}</td>
                        <td class="border-b border-gray-300 px-4 py-2 text-sm text-gray-800">{{ $user->status }}</td>
                        <td class="border-b border-gray-300 px-4 py-2 text-sm text-gray-800">{{ ($user->role == '1' ) ? 'Admin' : 'Terapeuta' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-4 text-gray-500">No hay usuarios disponibles.</div>
            @endif
        </div>
    </div>





</x-app-layout>