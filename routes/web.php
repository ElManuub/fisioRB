<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentDetailController;
use App\Http\Controllers\PatientConsultationController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\TherapyController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    //edit profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    //Details of appointments
    Route::get('/details', [AppointmentDetailController::class, 'index'])->name('details');
    Route::get('/showDetails', [AppointmentDetailController::class, 'show'])->name('details.show');
    Route::get('/appointments/{id}', [AppointmentDetailController::class, 'showDetails'])->name('appointments.show');

    //patients register and loggin
    Route::post('/storePatient', [PatientController::class, 'store']);
    Route::post('/showPatient', [PatientController::class, 'showPatient']);
    Route::post('/editPatient', [PatientController::class, 'edit']);

    //appointments
    Route::post('/storeAppointment', [AppointmentController::class, 'store']);
    Route::get('/showAppointments', [AppointmentController::class, 'show']);
    Route::get('/showAppointments/{id}', [AppointmentController::class, 'edit']);
    Route::post('/editAppointments/{id}', [AppointmentController::class, 'update']);
    Route::delete('/destroyAppointments/{id}', [AppointmentController::class, 'destroy']);

    // register total appointment
    Route::post('/registerAppointment', [AppointmentDetailController::class, 'total'])->name('appointment.total');

    //Consultation
    Route::get('/consultation', [AppointmentDetailController::class, 'consultation'])->name('consultation.appointments');
    Route::post('income/appointments', [AppointmentDetailController::class, 'showConsultations'])->name('income.appointments');
    Route::post('consultation/appointments', [AppointmentController::class, 'consultAppointment'])->name('appointments');

    //therapies
    Route::get('/therapies', [TherapyController::class, 'index'])->name('therapies');

    //completed 
    Route::post('/consult', [PatientConsultationController::class, 'index'])->name('consult.complete');
    Route::get('/consult/{id}', [PatientConsultationController::class, 'ticket'])->name('consult.ticket');

    //delete account
    Route::post('/account/delete', [UserController::class, 'deleteUser'])->name('account.deleteUser');
    Route::post('/users', [UserController::class, 'users'])->name('users');
    
    //export PDF
    Route::get('pdf', [AppointmentDetailController::class, 'imprimir'])->name('pdf');
    
});

require __DIR__.'/auth.php';
