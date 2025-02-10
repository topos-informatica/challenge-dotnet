<?php 

use App\Http\Controllers\ExternalApiController;

Route::get('/place', [ExternalApiController::class, 'getPlacesWithComments']);
Route::post('/reports', [ExternalApiController::class, 'createReport']);

