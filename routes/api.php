<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FreightTableController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BranchController;

Route::post('/upload-freight-csv', [FreightTableController::class, 'uploadCSV']);

Route::apiResource('freight-tables', FreightTableController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('branches', BranchController::class);