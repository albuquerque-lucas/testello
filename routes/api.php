<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FreightTableController;

Route::get('/freight-tables', [FreightTableController::class, 'index']);
Route::get('/freight-tables/{id}', [FreightTableController::class, 'show']);
Route::post('/upload-freight-csv', [FreightTableController::class, 'uploadCSV']);
