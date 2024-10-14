document.getElementById('select-type').addEventListener('change', function() {
  const selectedValue = this.value;
  document.getElementById('register-account').classList.toggle('hidden', selectedValue !== 'users');
  document.getElementById('delete-account').classList.toggle('hidden', selectedValue !== 'delete');
});