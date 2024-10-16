// Obtener todos los checkboxes dinámicamente
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

// Evento para el select (primer pago)
firstDay?.addEventListener('change', (e) => {
  // Restar el valor previo si lo había
  subtotal -= parseFloat(firstDay.dataset.previousValue) || 0;
  // Sumar el valor actual
  subtotal += parseFloat(e.target.value) || 0;
  // Guardar el valor actual para el siguiente cambio
  firstDay.dataset.previousValue = e.target.value;
  updateTotal(); // Actualiza el total
});

// Evento para los checkboxes
checks.forEach(terapia => {
  terapia.addEventListener('change', (event) => {
    const price = parseFloat(terapia.dataset.price) || 0;
    if (terapia.checked) {
      subtotal += price; // Sumar si se selecciona
    } else {
      subtotal -= price; // Restar si se desmarca
    }
    updateTotal(); // Actualiza el total
  });
});

// Evento para el campo "extra"
extra?.addEventListener('input', (monto) => {
  const extraValue = parseFloat(monto.target.value) || 0;
  // Restar el valor previo del extra si lo había
  subtotal += extraValue - (parseFloat(extra.dataset.previousValue) || 0);
  extra.dataset.previousValue = extraValue; // Guardar el valor actual del extra
  updateTotal(); // Actualiza el total
});

// Confirmación de envío del formulario
form?.addEventListener('submit', (event) => {
  if (!confirm('¿Todos tus datos están correctos?')) {
    event.preventDefault(); 
  }
});
