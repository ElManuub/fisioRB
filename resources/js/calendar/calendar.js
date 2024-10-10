import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';

document.addEventListener('DOMContentLoaded', function () {
  let calendarEl = document.getElementById('calendar');
  const formNoExist = document.getElementById('client-new-register');
  const formExist = document.getElementById('client-exist');
  const ModalEdit = document.querySelector('#modal-edit');
  const formEdit = document.querySelector('#client-edit-appointment-modal');
  const formAppointment = document.getElementById('client-new-appointment');

  //link
  const link = 'http://127.0.0.1:8000';

  //calendario y funciones
  let calendar = new Calendar(calendarEl, {
    plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
    initialView: 'timeGridWeek',
    locale: esLocale,

    views: {
      timeGrid: {
        slotMinTime: '00:00:00',
        slotMaxTime: '24:00:00'
      }
    },

    slotLabelFormat: {
      hour: '2-digit',
      minute: '2-digit',
      hour12: true,
      meridiem: 'short',
    },

    eventTimeFormat: {
      hour: 'numeric',
      minute: 'numeric',
      hour12: true
    },

    showNonCurrentDates: false,

    eventBackgroundColor: '#2874a6',

    allDaySlot: false,

    titleFormat: {
      day: '2-digit',
      month: 'short',
      year: '2-digit'
    },

    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'timeGridWeek,timeGridDay,listWeek'
    },

    height: 'auto',

    events: `${link}/showAppointments`,
  

    //cambiar el color del evento
    eventDataTransform: function (eventData) {
      let eventColor = '';
    
      // Asigna un color basado en el estado del evento
      if (eventData.status === 'pendiente') {
        eventColor = '#f39c12'; 
      } else if (eventData.status === 'completo') {
        eventColor = '#27ae60'; 
      } else if (eventData.status === 'cancelado') {
        eventColor = '#e74c3c'; 
      }
    
      return {
        id: eventData.id,
        title: eventData.patient_id,
        start: eventData.date + 'T' + eventData.start_time,
        end: eventData.date + 'T' + eventData.end_time,
        color: eventColor // Asigna el color al evento
      };
    },
    

    dateClick: function (info) {
      formatDate(info);
    },

    eventClick: async function (event) {
      const idAppointment = event.event._def.publicId;

      try {
        const response = await fetch(`${link}/showAppointments/${idAppointment}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },

        });

        if (response.ok) {
          const dataResponse = await response.json();
          console.log(dataResponse);

          ModalEdit.classList.remove('hidden');
          document.querySelector('#patient_edit').value = dataResponse.data.patient;
          document.querySelector('#phone_edit').value = dataResponse.data.phone_number || '';
          document.querySelector('#client_id_edit').value = dataResponse.data.id || '';
          document.querySelector('#appointment-date-edit').value = dataResponse.data.date || '';
          document.querySelector('#start-edit').value = dataResponse.data.start_time || '';
          document.querySelector('#end-edit').value = dataResponse.data.end_time || '';
          document.querySelector('#note-edit').value = dataResponse.data.note || '';
          document.querySelector('#appointment_id').value = dataResponse.data.appointment_id || '';

        } else {
          console.error('Error en la respuesta:', response.status, response.statusText);
        }
      } catch (error) {
        console.error('Error en la solicitud:', error);
      }
    }


  });
  calendar.render();

  //buscar cliente
  document.getElementById('btn-search').addEventListener('click', async () => {
    const data = new FormData(formExist);

    try {
      const response = await fetch(`${link}/showPatient`, {
        method: 'POST',
        body: data
      });

      if (response.ok) {
        const result = await response.json();
        console.log(result);

        document.querySelector('#new-name').value = result.data.name || '';
        document.querySelector('#new-phone').value = result.data.phone_number || '';
        document.querySelector('#client_id').value = result.data.id || '';
        formExist.classList.add('hidden');
        document.querySelector('#client-search').classList.add('hidden');
        formAppointment.classList.remove('hidden');

        alert(`Paciente encontrado`);

      } else {

        const errorResult = await response.json();
        alert(`${errorResult.message}` || 'No se encontró ningún cliente con los datos proporcionados.');

      }
    } catch (error) {
      console.error('Error en la petición:', error);
      alert('Ocurrió un error, intentelo mas tarde.');
    }
  });

  //registrar un nuevo cliente
  document.getElementById('btn-register').addEventListener('click', async () => {
    const data = new FormData(formNoExist);

    try {
      const response = await fetch(`${link}/storePatient`, {
        method: 'POST',
        body: data
      });

      const result = await response.json();

      if (response.ok) {
        console.log(result);

        document.querySelector('#new-name').value = result.data.name || '';
        document.querySelector('#new-phone').value = result.data.phone_number || '';
        document.querySelector('#client_id').value = result.data.id || '';

        formNoExist.classList.add('hidden');
        document.querySelector('#client-search').classList.add('hidden');
        formAppointment.classList.remove('hidden');

        alert('Registro exitoso');

      } else {

        alert(result.message);

      }
    } catch (error) {
      const errorSV = await response.json();
      console.log('Error en la petición:', error);
      alert(errorSV.message);
    }
  });

  //Registrar una nueva cita
  document.querySelector('#btn-appointment').addEventListener('click', async () => {
    const data = new FormData(formAppointment);
    try {
      const response = await fetch(`${link}/storeAppointment`, {
        method: 'POST',
        body: data,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
      });


      if (response.ok) {
        calendar.refetchEvents();
        const result = await response.json();
        alert('Registro exitoso');
        this.location.reload();
        console.log(result);

      } else {
        const err = await response.json();
        console.log(err.error);
        alert(err.message);
      }

    } catch (error) {
      console.log('Error en la petición:', error);
      alert('Ocurrió un error al enviar los datos de cita');
    }

  })

  //editar informacion cita
  document.querySelector('#btn-update-edit').addEventListener('click', async (e) => {
    const data = new FormData(formEdit);
    e.preventDefault();


    const id = formEdit.querySelector('input[id="appointment_id"]').value;

    try {
      const response = await fetch(`${link}/editAppointments/${id}`, {
        body: data,
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        }
      });

      if (response.ok) {
        const data = await response.json();
        alert('informacion actualizada con exito.');
        this.location.reload();
        console.log(data);
      } else {
        const err = await response.json();
        alert(err.message);
        console.log(err);
      }
    } catch (error) {
      console.log(error)
    }


  })

  //Eliminar informacion cita
  document.querySelector('#btn-cancel-edit').addEventListener('click', async (e) => {
    const data = new FormData(formEdit);
    e.preventDefault();

    const id = formEdit.querySelector('input[id="appointment_id"]').value;

    if (confirmarAccion() == 0) {
      return
    }

    try {
      const response = await fetch(`${link}/destroyAppointments/${id}`, {
        body: data,
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        }
      });

      if (response.ok) {
        const data = await response.json();

        alert('Cita eliminada con exito');
        this.location.reload();

      } else {
        const err = await response.json();
        alert(err.message);
        console.log(err);
      }
    } catch (error) {
      console.log(error)
    }


  })

  //formatear fechas
  function formatDate(info) {
    document.getElementById('modal-search').classList.remove('hidden');
    document.getElementById('client-search').classList.remove('hidden');

    const day = String(info.date.getDate()).padStart(2, '0');  // Día del mes
    const month = String(info.date.getMonth() + 1).padStart(2, '0');  // Mes (0-11 + 1)
    const year = String(info.date.getFullYear());

    const hour = String(info.date.getHours()).padStart(2, '0');
    const minutes = String(info.date.getMinutes()).padStart(2, '0');

    // Formatea la hora en el formato HH:MM
    const timeString = `${hour}:${minutes}`;
    // Formatea la fecha en el formato YYYY-MM-DD
    const timeDate = `${year}-${month}-${day}`;


    document.querySelector('#start').value = timeString;
    document.querySelector('#appointment-date').value = timeDate;
  }

  // Función para confirmar una acción
  function confirmarAccion() {
    const respuesta = confirm('¿Estás seguro de que deseas cancelar la cita?');

    if (respuesta) {
      return 1;
    } else {
      return 0;
    }
  }

});
