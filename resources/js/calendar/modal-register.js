const modalSearch =  document.getElementById('modal-search');
const clientExist = document.getElementById('client-exist');
const newRegister = document.getElementById('client-new-register');
const closeModal = document.querySelector('#close-modal');
const closeEdit = document.querySelector('#close-modal-edit');
const newAppointment = document.getElementById('client-new-appointment');

const modalEdit = document.querySelector('#modal-edit');
const selectClientRegister = document.querySelector('.client-register');

//Modal hidden
closeModal.addEventListener('click', function () {
    modalSearch.classList.add('hidden');
    clientExist.classList.add('hidden');
    newRegister.classList.add('hidden');
    newAppointment.classList.add('hidden');

    //select
    selectClientRegister.selectedIndex = 0;

});

//edit modal
closeEdit.addEventListener('click', ()=>{
    if(!modalEdit.classList.contains('hidden')){
        modalEdit.classList.add('hidden');
    }
})

//Search client (Modal)
selectClientRegister.addEventListener('change', function (event) {
    if (this.value === 'yes') {
        clientExist.classList.remove('hidden');
        newRegister.classList.add('hidden');
    } else if (this.value === 'no') {
        clientExist.classList.add('hidden');
        newRegister.classList.remove('hidden');
    } else {
        clientExist.classList.add('hidden');
        newRegister.classList.add('hidden');
    }


});




