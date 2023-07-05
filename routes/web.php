<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::fallback(function () {
    return "<h1>Sorry, the page you are looking for does not exist.</h1>";
});


Route::get('/', function () {
    return view('welcome');
});

Route::controller(ContactController::class)->name('contacts.')->group(function() {
    Route::get('/contacts', 'index')->name('index');
    Route::get('/contacts/create', 'create')->name('create');
    Route::post('/contacts/store', 'store')->name('store');
    Route::get('/contacts/{id}', 'show')->name('show');
    Route::put('/contacts/{id}', 'update')->name('update');
    Route::delete('/contacts/{id}', 'destroy')->name('destroy');
    Route::get('/contacts/{id}/edit', 'edit')->name('edit');
});