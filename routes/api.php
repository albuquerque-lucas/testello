<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FreightTableController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BranchController;

Route::get('/freight-tables', [FreightTableController::class, 'index']);
Route::get('/freight-tables/{id}', [FreightTableController::class, 'show']);
Route::post('/upload-freight-csv', [FreightTableController::class, 'uploadCSV']);

Route::apiResource('customers', CustomerController::class);
Route::apiResource('branches', BranchController::class);
