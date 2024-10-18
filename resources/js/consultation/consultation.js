// forms
const formClient = document.querySelector('#form-client');
const formClientEdit = document.querySelector('#form-client-edit');
const formAppointment = document.querySelector('#appointment-form');

// dynamic divs
const divClient = document.getElementById('client-div');
const divConsult = document.getElementById('consultation-form');
const divIncome = document.getElementById('income-form');
const divAppointments = document.getElementById('appointment-div');

//edit client
const editClient = document.querySelector('#client-div-edit');
const tableIncome = document.querySelector('#income-form-div');
const editAppointment = document.querySelector('#appointment-div-consult');
const tableConsult = document.querySelector('#consult-form-div');

//link
const link = 'http://127.0.0.1:8000';

//selector
document.getElementById('select-type').addEventListener('change', function () {

  if (this.value === 'clientes') {

    divClient.classList.remove('hidden');
    divConsult.classList.add('hidden');
    divIncome.classList.add('hidden');
    divAppointments.classList.add('hidden');


    if (!editClient.classList.contains('hidden') || !tableIncome.classList.contains('hidden') || !editAppointment.classList.contains('hidden') || !tableConsult.classList.contains('hidden')) {
      editClient.classList.add('hidden');
      editAppointment.classList.add('hidden');
      tableIncome.classList.add('hidden');
      tableConsult.classList.add('hidden');
    }

  } else if (this.value === 'consultas') {

    divConsult.classList.remove('hidden');
    divClient.classList.add('hidden');
    divIncome.classList.add('hidden');
    tableIncome.classList.add('hidden');
    divAppointments.classList.add('hidden');


    if (!editClient.classList.contains('hidden') || !tableIncome.classList.contains('hidden') || !editAppointment.classList.contains('hidden') || !tableConsult.classList.contains('hidden')) {
      editClient.classList.add('hidden');
      tableIncome.classList.add('hidden');
      editAppointment.classList.add('hidden');
      tableConsult.classList.add('hidden');


    }

  } else if (this.value === 'ingresos') {

    divIncome.classList.remove('hidden');
    divConsult.classList.add('hidden');
    divClient.classList.add('hidden');
    divAppointments.classList.add('hidden');

    if (!editClient.classList.contains('hidden') || !editAppointment.classList.contains('hidden') || !tableConsult.classList.contains('hidden')) {
      editClient.classList.add('hidden');
      editAppointment.classList.add('hidden');
      tableConsult.classList.add('hidden');


    }

  } else if (this.value === 'citas') {

    divAppointments.classList.remove('hidden');
    divClient.classList.add('hidden');
    divConsult.classList.add('hidden');
    divIncome.classList.add('hidden');

    if (!editClient.classList.contains('hidden') || !tableIncome.classList.contains('hidden') || !tableConsult.classList.contains('hidden')) {
      editClient.classList.add('hidden');
      tableIncome.classList.add('hidden');
      tableConsult.classList.add('hidden');


    }
  }

});

// buscar cliente
document.getElementById('search-client').addEventListener('click', async () => {
  const dataForm = new FormData(formClient);

  try {
    const response = await fetch(`${link}/showPatient`, {
      method: 'POST',
      body: dataForm,
      headers: {
        'Accept': 'application/json'
      }
    });

    if (response.ok) {
      const result = await response.json();
      console.log(result);
      alert(`Paciente encontrado`);

      // Mostrar los datos del paciente
      editClient.classList.remove('hidden');
      document.querySelector('#patient_id').value = result.data.id
      document.querySelector('#patient').value = result.data.name
      document.querySelector('#phone_number').value = result.data.phone_number
      document.querySelector('#officer_id').value = result.data.office_id
      document.querySelector('#officer').value = result.data.office.name;

      // Ocultar el div de búsqueda
      divClient.classList.add('hidden');

    } else {
      const errorResult = await response.json();
      console.log(errorResult);
      alert('Paciente no encontrado.\n' + errorResult.message);
    }
  } catch (error) {
    console.error('Error en la petición:', error);
    alert('Ocurrió un error, intentelo más tarde.');
  }
});

//editar cliente
document.getElementById('search-client-edit').addEventListener('click', async () => {
  const dataForm = new FormData(formClientEdit);

  try {
    const response = await fetch(`${link}/editPatient`, {
      method: 'POST',
      body: dataForm,
      headers: {
        'Accept': 'application/json'
      }
    });

    if (response.ok) {
      const result = await response.json();
      console.log(result);

      alert("Paciente actualizado con exito.");
      location.reload();

    } else {
      const errorResult = await response.json();
      console.log(errorResult);
      alert(errorResult.message); // Mensaje claro para el usuario
    }
  } catch (error) {
    console.error('Error en la petición:', error);
    alert('Ocurrió un error, intentelo más tarde.');
  }
});

//tabla ganancias
document.getElementById('search-income-form').addEventListener('submit', async (event) => {
  event.preventDefault();

  const startDate = document.getElementById('start_date').value;
  const endDate = document.getElementById('end_date').value;
  tableIncome.classList.remove('hidden');
  divIncome.classList.add('hidden');

  try {
    const response = await fetch(`${link}/income/appointments`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json', // Indicar que estamos enviando JSON
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Agregar token CSRF
      },
      body: JSON.stringify({
        start_date: startDate,
        end_date: endDate
      })
    });

    if (response.ok) {
      const result = await response.json();

      console.log(result);

      const incomeInfoTableBody = document.getElementById('income-info'); // Obtener el tbody de la tabla
      incomeInfoTableBody.innerHTML = ''; // Limpiar resultados anteriores
      let subTotal = 0;

      // Numerar las citas
      result.data.forEach((appointmentDetail, index) => {
        const { appointment, total, date } = appointmentDetail;
        const patientName = appointment.patient.name;
        const userName = appointment.user.name;
        const office = appointment.user.office.name;
        const dateNow = new Date(date);

        incomeInfoTableBody.innerHTML += `
                  <tr>
                      <td class="border border-gray-300 p-2">${index + 1}</td> <!-- Numeración -->
                      <td class="border border-gray-300 p-2">${patientName}</td>
                      <td class="border border-gray-300 p-2">${userName}</td>
                      <td class="border border-gray-300 p-2">${office}</td>
                      <td class="border border-gray-300 p-2">${dateNow.toLocaleDateString('en-CA')}</td>
                      <td class="border border-gray-300 p-2">$${total}</td>
                  </tr>
              `;
        subTotal += parseFloat(total);
      });
      incomeInfoTableBody.innerHTML +=
        `<tr>
          <td colspan="5" class="border border-gray-300 p-2 bg-gray-200">Total</td>
          <td class="border border-gray-300 p-2">$${parseFloat(subTotal).toFixed(2)}</td>
          </tr>`

    } else {
      const errorResult = await response.json();
      console.error(errorResult);
      alert(errorResult.message || 'Error desconocido');
    }
  } catch (error) {
    console.error('Error en la petición:', error);
    alert('Ocurrió un error, intente más tarde.');
  }
});

//buscar citas
document.getElementById('appointment-form').addEventListener('submit', async (event) => {
  event.preventDefault();

  const formData = new FormData(event.target);
  const data = Object.fromEntries(formData.entries());
  const appointmentsTable = document.querySelector('#appointments-info');

  try {
    const response = await fetch(`${link}/consultation/appointments`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(data) // Convertir el objeto a JSON antes de enviarlo
    });

    if (response.ok) {
      const responseData = await response.json();
      console.log(responseData);

      // Limpiar el cuerpo de la tabla antes de agregar nuevos resultados
      appointmentsTable.innerHTML = '';
      divAppointments.classList.add('hidden');
      editAppointment.classList.remove('hidden');


      if (Array.isArray(responseData.data) && responseData.data.length > 0) {
        responseData.data.forEach((item, index) => {
          appointmentsTable.innerHTML += `
  <tr>
    <td class="border border-gray-300 p-2">${index + 1}</td> 
    <td class="border border-gray-300 p-2">${item.user_id || 'N/A'}</td> 
    <td class="border border-gray-300 p-2">${item.patient_id || 'N/A'}</td> 
    <td class="border border-gray-300 p-2">${item.date || 'N/A'}</td> 
    <td class="border border-gray-300 p-2 ${item.status === 'cancelado' ? 'text-red-500' : item.status === 'pendiente' ? 'text-cyan-500' : 'text-green-500'}">
      ${item.status || 'N/A'}
    </td> 
  </tr>
`;

        });
      } else {
        appointmentsTable.innerHTML += `
          <tr>
            <td colspan="5" class="border border-gray-300 p-2 text-center">No hay citas encontradas para este día.</td>
          </tr>
        `;
      }


    } else {
      // Manejo de error si la respuesta no es exitosa
      const errorData = await response.json();
      console.error('Error al obtener citas:', errorData);
      alert('No se pudo obtener las citas. Por favor, verifica los datos.');
    }
  } catch (error) {
    console.error('Error de red:', error);
    alert('Ocurrió un error de red. Por favor, inténtalo de nuevo más tarde.');
  }
});

// Buscar consultas terminadas
document.querySelector('#form-consultation-search').addEventListener('submit', async (event) => {
  event.preventDefault();

  const formData = new FormData(event.target);
  const data = Object.fromEntries(formData.entries());

  try {
    const response = await fetch(`${link}/consult`, {
      method: 'POST',
      body: JSON.stringify(data),
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
    });

    const result = await response.json();

    if (response.ok) {
      console.log(result);
      const table = document.querySelector('#consult-info');
      divConsult.classList.add('hidden');
      document.querySelector('#consult-form-div').classList.remove('hidden');
      table.innerHTML = '';

      result.data.forEach(detail => {
        const { appointment, date, id } = detail;
        const employee = appointment.user.name;
        const office = appointment.patient.office.name;
        const patient = appointment.patient.name;
        const dateNow = new Date(date);


        table.innerHTML += `
        <tr class="hover:bg-gray-100 hover:scale-105 transition-transform cursor-pointer">
          <td class="border border-gray-300 p-2 text-center font-semibold">${id}</td> <!-- Numeración -->
          <td class="border border-gray-300 p-2">${patient}</td>
          <td class="border border-gray-300 p-2">${employee}</td>
          <td class="border border-gray-300 p-2">${office}</td>
          <td class="border border-gray-300 p-2 text-center">${dateNow.toLocaleDateString('en-CA')}</td>
          <td class="border border-gray-300 p-2 text-center">
            <form action="${link}/consult/${id}" method="get">
              <button type="submit" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600">
                ver
              </button>
            </form>
          </td>
        </tr>`;
      
      


      });

    } else {
      console.error('Error:', result.error);
    }
  } catch (error) {
    console.error('Error en la solicitud:', error);
  }
});





