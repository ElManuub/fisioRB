const checks = document.querySelectorAll('input[type="checkbox"]');
const firstDay = document.querySelector('#first_pay');
const form = document.querySelector('#appointment');
let total = document.querySelector('#total');
const extra = document.querySelector('#extra');
const guardar = document.querySelector('#enviar');

let subtotal = 0;

// Función para actualizar el total
function updateTotal() {
  total.value = subtotal.toFixed(2); // Actualiza el total, formateado a dos decimales
}

// Evento para el select
firstDay.addEventListener('change', (e) => {
  // Primero restamos el valor antiguo si hay uno seleccionado
  subtotal -= parseFloat(firstDay.dataset.previousValue) || 0; 
  // Asignamos el nuevo valor
  subtotal += parseFloat(e.target.value) || 0; 
  // Guardamos el valor actual para futuros cambios
  firstDay.dataset.previousValue = e.target.value; 
  updateTotal(); // Actualiza el total
});

// Evento para los checkboxes
checks.forEach(terapia => {
  terapia.addEventListener('change', (event) => {
    if (terapia.checked) {
      subtotal += parseFloat(event.target.value) || 0; // Suma el valor del checkbox
    } else {
      subtotal -= parseFloat(event.target.value) || 0; // Resta el valor si se desmarca
    }
    updateTotal(); // Actualiza el total
  });
});

// Evento para el extra
extra.addEventListener('input', (monto) => {
  const extraValue = parseFloat(monto.target.value) || 0;
  // Si el valor es mayor a 0, lo sumamos, sino restamos el valor previo
  subtotal += extraValue - (parseFloat(extra.dataset.previousValue) || 0);
  extra.dataset.previousValue = extraValue; // Guardamos el valor actual
  updateTotal(); // Actualiza el total
});

//confirmacion
form.addEventListener('submit', (event) => {
  if (!confirm('¿Todos tus datos están correctos?')) {
    event.preventDefault(); 
  }
});
