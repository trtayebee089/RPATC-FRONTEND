<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvitationRegistrationController;

Route::get('/registration/{code}', [InvitationRegistrationController::class, 'registration']);
Route::post('/registration/{code}/store', [InvitationRegistrationController::class, 'store'])->name('registration.submit');
Route::post('/check-nid', [InvitationRegistrationController::class, 'checkNid'])->name('nid.check');

Route::fallback(function () {
    return redirect()->away('https://patc.rajshahidiv.gov.bd');
});
