<?php

use Illuminate\Support\Facades\Route;
use Scouser03\Nova4Multiselect\Http\Controllers\MultiSelectController;

Route::post('delete', [MultiSelectController::class, 'destroy']);
Route::post('update', [MultiSelectController::class, 'update']);