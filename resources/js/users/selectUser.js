document.getElementById('select-type').addEventListener('change', function() {
  const selectedValue = this.value;

  // Ocultar todos los formularios al principio
  document.getElementById('register-account').classList.add('hidden');
  document.getElementById('delete-account').classList.add('hidden');
  document.getElementById('consult-account').classList.add('hidden');

  // Mostrar el formulario correspondiente
  if (selectedValue === 'users') {
    document.getElementById('register-account').classList.remove('hidden');
  } else if (selectedValue === 'delete') {
    document.getElementById('delete-account').classList.remove('hidden');
  } else if (selectedValue === 'consult') {
    document.getElementById('consult-account').classList.remove('hidden');
  }
});
