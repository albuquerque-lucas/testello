<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FreightTableController;

Route::post('/upload-freight-csv', [FreightTableController::class, 'uploadCSV']);
