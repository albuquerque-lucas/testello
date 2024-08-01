<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FreightTableController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BranchController;

Route::post('/upload-freight-csv', [FreightTableController::class, 'uploadCSV']);

Route::post('/freight-tables/bulkDelete', [FreightTableController::class, 'bulkDelete']);
Route::post('/freight-tables/delete', [FreightTableController::class, 'destroy']);
Route::apiResource('freight-tables', FreightTableController::class)->except(['destroy']);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('branches', BranchController::class);