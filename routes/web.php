<?php

use Illuminate\Support\Facades\Route;

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
    abort(404);
});
// Main routes that contains the tables of the database
Route::get('/', function () {
    return view('pages.home');
})->name('Home');
Route::get('/entries', 'App\Http\Controllers\EntryController@index')->name('Entry.index');
Route::get('/items', 'App\Http\Controllers\ItemController@index')->name('Item.index');
Route::get('/customers', 'App\Http\Controllers\CustomerController@index')->name('Customer.index');
// Search routes for searching in the tables
Route::get('/entries/search', 'App\Http\Controllers\EntryController@search')->name('Entry.search');
Route::get('/items/search', 'App\Http\Controllers\ItemController@search')->name('Item.search');
Route::get('/customers/search', 'App\Http\Controllers\CustomerController@search')->name('Customer.search');
// Create routes to render the create forms
Route::get('/entries/create', 'App\Http\Controllers\EntryController@create')->name('Entry.create');
Route::get('/items/create', 'App\Http\Controllers\ItemController@create')->name('Item.create');
Route::get('/customers/create', 'App\Http\Controllers\CustomerController@create')->name('Customer.create');
// Store routes to store the data in the database
Route::post('/entries/store', 'App\Http\Controllers\EntryController@store')->name('Entry.store');
Route::post('/items/store', 'App\Http\Controllers\ItemController@store')->name('Item.store');
Route::post('/customers/store', 'App\Http\Controllers\CustomerController@store')->name('Customer.store');
// Delete routes to delete the data from the database
Route::delete('/entries/delete', 'App\Http\Controllers\EntryController@delete')->name('Entry.delete');
Route::delete('/items/delete', 'App\Http\Controllers\ItemController@delete')->name('Item.delete');
Route::delete('/customers/delete', 'App\Http\Controllers\CustomerController@delete')->name('Customer.delete');
// Edit routes to render the edit forms
Route::get('/entries/{id}/edit', 'App\Http\Controllers\EntryController@edit')->name('Entry.edit');
Route::get('/items/{id}/edit', 'App\Http\Controllers\ItemController@edit')->name('Item.edit');
Route::get('/customers/{id}/edit', 'App\Http\Controllers\CustomerController@edit')->name('Customer.edit');
// Update routes to update the data in the database
Route::patch('/entries/{id}/update', 'App\Http\Controllers\EntryController@update')->name('Entry.update');
Route::patch('/items/{id}/update', 'App\Http\Controllers\ItemController@update')->name('Item.update');
Route::patch('/customers/{id}/update', 'App\Http\Controllers\CustomerController@update')->name('Customer.update');
// Routes to show the records of the customers and items in entries
Route::get('/items/{id}/records', 'App\Http\Controllers\ItemController@records')->name('Item.records');
Route::get('/customers/{id}/records', 'App\Http\Controllers\CustomerController@records')->name('Customer.records');
// Export route to export the entries to pdf
Route::post('/entries/export', 'App\Http\Controllers\EntryController@export')->name('Entry.export');
// Trash bin routes
Route::get('/entries/trash', 'App\Http\Controllers\EntryController@trash')->name('Entry.trash');
Route::get('/items/trash', 'App\Http\Controllers\ItemController@trash')->name('Item.trash');
Route::get('/customers/trash', 'App\Http\Controllers\CustomerController@trash')->name('Customer.trash');
// Trash restoration routes
Route::patch('/entries/trash/restore', 'App\Http\Controllers\EntryController@restore')->name('Entry.restore');
Route::patch('/items/trash/restore', 'App\Http\Controllers\ItemController@restore')->name('Item.restore');
Route::patch('/customers/trash/restore', 'App\Http\Controllers\CustomerController@restore')->name('Customer.restore');
// Trash force delete routes
Route::delete('/entries/trash/force-delete', 'App\Http\Controllers\EntryController@forceDelete')->name('Entry.forceDelete');
Route::delete('/items/trash/force-delete', 'App\Http\Controllers\ItemController@forceDelete')->name('Item.forceDelete');
Route::delete('/customers/trash/force-delete', 'App\Http\Controllers\CustomerController@forceDelete')->name('Customer.forceDelete');