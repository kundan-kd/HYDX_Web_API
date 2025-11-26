<?php

use App\Http\Controllers\api\contact\ContactController;
use App\Http\Controllers\api\reservation\ReservationController;
use Illuminate\Support\Facades\Route;

Route::post('/add-reservation-data',[ReservationController::class,'addResData']);
Route::get('/get-reservation-data',[ReservationController::class,'getResData']);
Route::post('/add-contact-data',[ContactController::class,'addContact']);
Route::get('/get-contact-data',[ContactController::class,'getContact']);
?>