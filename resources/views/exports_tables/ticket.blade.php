<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">
    <div class="w-full mx-auto bg-white p-8 rounded-lg shadow-lg mt-10 border border-gray-300">
        <!-- Encabezado -->
        <div class="header text-center py-4 rounded-lg">
            <h1 class="text-3xl font-bold">FISIO R.B</h1>
        </div>

        <!-- Información de la Sucursal -->
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-800 border-separator mb-4">Información de la Sucursal</h2>
            <hr>
            <p class="text-gray-700"><strong>Sucursal:</strong> {{ $office->name }}</p>
            <p class="text-gray-700"><strong>Dirección:</strong> {{ $office->address }}, {{ $office->town }}</p>
            <p class="text-gray-700"><strong>CP:</strong> {{ $office->postalCode }}</p>
            <p class="text-gray-700"><strong>Teléfono:</strong> {{ $office->phone_number }}</p>
        </div>

        <!-- Información del Paciente -->
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-800 border-separator mb-4">Información de la consulta</h2>
            <hr>
            <p class="text-gray-700"><strong>Paciente:</strong> {{ $patient->name }}</p>
            <p class="text-lg"><strong>Fecha de la cita:</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</p>
            <p class="text-gray-700"><strong>Horario:</strong> {{ $appointment->start_time }} a {{ $appointment->end_time }}</p>
            <p class="text-gray-700"><strong>Atendió:</strong> {{ $user->name }}</p>
        </div>

        <!-- Detalle de las Terapias -->
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-800 border-separator mb-4">Detalle</h2>
            <hr>
            <ul class="list-disc list-inside mb-4">
                @foreach ($consultationDetail->therapies as $therapy)
                <li class="text-gray-700">{{ $therapy->name }} -
                    @if ($therapy->discount_amount == 0 || $therapy->discount_end <= now())
                        {{-- Precio sin descuento --}}
                        ${{ $therapy->price }}
                    @else
                        {{-- Precio con descuento --}}
                      
                            <del>${{ $therapy->price }}</del>
                            ${{ number_format($therapy->price * (1 - $therapy->discount_amount / 100), 2) }}
                            (Descuento del %{{ number_format($therapy->discount_amount, 0) }})                       
                    @endif
                @endforeach
            </ul>
            <p class="text-gray-700">
                @if ($query_type >= 800)
                <strong>Primer consulta:</strong> ${{ $query_type }}
                @else
                <strong>Consulta normal:</strong> ${{ $query_type }}
                @endif
            </p>
            <p class="text-gray-700"><strong>Extra:</strong> ${{ $extra ?? 'N/A' }}</p>
            <p class="text-xl font-bold text-gray-800 mt-4"><strong>Total:</strong> ${{ number_format($total, 2) }}</p>
        </div>
        <hr>
        <div class="text-center mt-8">
            <p class="text-sm text-gray-600">Gracias por su preferencia.</p>
        </div>
    </div>
</body>

</html>