<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/getAllInvoices', [InvoiceController::class, 'getAllInvoices']);
Route::get('/getInvoice/{id}', [InvoiceController::class, 'getInvoice']);
Route::get('/searchInvoice', [InvoiceController::class, 'searchInvoice']);
Route::get('/createInvoice', [InvoiceController::class, 'createInvoice']);
Route::post('/addInvoice', [InvoiceController::class, 'addInvoice']);

Route::get('/customers', [CustomerController::class, 'allCustomers']);
Route::get('/products', [ProductController::class, 'allProducts']);