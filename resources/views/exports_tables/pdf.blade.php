<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listado de Ingresos</title>
  <!-- Incluye aquí el CSS de Tailwind -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9fafb; /* Fondo gris claro */
      color: #333; /* Color del texto */
    }
    
    header {
      text-align: center;
      margin-bottom: 20px;
    }

    h1 {
      font-size: 2rem;
      margin-bottom: 5px;
    }

    h2 {
      font-size: 1.25rem;
      color: #555; /* Color más claro para el subtítulo */
    }

    .table-container {
      display: flex; /* Usar flexbox para centrar */
      justify-content: center; /* Centrar horizontalmente */
      margin: 20px 0; /* Espaciado superior e inferior */
    }

    table {
      border-collapse: collapse;
      width: 100%; /* Hacer la tabla más ancha */
      max-width: 800px; /* Ancho máximo de la tabla */
      margin-bottom: 20px; /* Espaciado inferior */
    }

    th, td {
      padding: 12px 15px; /* Espaciado interior */
      text-align: left; /* Alinear texto a la izquierda */
    }

    th {
      background-color: #4a5568; /* Fondo gris oscuro para el encabezado */
      color: #fff; /* Color del texto en el encabezado */
    }

    tr:nth-child(even) {
      background-color: #edf2f7; /* Fondo gris claro para filas pares */
    }

    tr:hover {
      background-color: #e2e8f0; /* Color al pasar el ratón */
    }

    .subtotal-row {
      background-color: #f6e05e; /* Fondo amarillo claro para subtotal */
      font-weight: bold; /* Negrita para el subtotal */
    }
  </style>
</head>

<body>
  <header>
    <h1>FISIO RB</h1>  
    <p>Listado de ingresos de {{ $dates['start_date'] }} a {{ $dates['end_date'] }}</p>
  </header>
  
  <section>
    <div class="table-container">
      <table class="min-w-full border border-gray-300">
        <thead>
          <tr>
            <th class="border border-gray-300">Num</th>
            <th class="border border-gray-300">Paciente</th>
            <th class="border border-gray-300">Terapeuta</th>
            <th class="border border-gray-300">Sucursal</th>
            <th class="border border-gray-300">Fecha</th>
            <th class="border border-gray-300">Total</th>
          </tr>
        </thead>
        <tbody>
          @php
            $subTotal = 0;
          @endphp
          @foreach ($incomes as $index => $income)
          <tr>
            <td class="border border-gray-300">{{ $index + 1 }}</td> <!-- Muestra el índice -->
            <td class="border border-gray-300">{{ $income->appointment->patient->name }}</td> 
            <td class="border border-gray-300">{{ $income->appointment->user->name }}</td>
            <td class="border border-gray-300">{{ $income->appointment->user->office->name }}</td> 
            <td class="border border-gray-300">{{ \Carbon\Carbon::parse($income->date)->format('Y-m-d') }}</td>
            <td class="border border-gray-300">${{ $income->total }}</td>  
          </tr>
          {{$subTotal += $income->total;}}
          @endforeach
          <tr class="subtotal-row">
            <td colspan="5" class="border border-gray-300 text-right">Subtotal</td>
            <td class="border border-gray-300">${{ number_format($subTotal, 2) }}</td>  
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</body>

</html>
