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

Route::get('/', 'App\Http\Controllers\MainTableController@index')->name('home');

Route::get('/items', 'App\Http\Controllers\ItemController@index')->name('items');

Route::get('/customers', 'App\Http\Controllers\CustomerController@index')->name('customers');

Route::get('/create', 'App\Http\Controllers\MainTableController@create')->name('addEntry');

Route::get('/items/create', 'App\Http\Controllers\ItemController@create')->name('addItem');

Route::get('/customers/create', 'App\Http\Controllers\CustomerController@create')->name('addCustomer');

Route::post('/store', 'App\Http\Controllers\MainTableController@store')->name('storeEntry');

Route::post('/items/store', 'App\Http\Controllers\ItemController@store')->name('storeItem');

Route::post('/customers/store', 'App\Http\Controllers\CustomerController@store')->name('storeCustomer');

Route::get('/{id}/delete', 'App\Http\Controllers\MainTableController@delete')->name('deleteEntry');

Route::get('/items/{id}/delete', 'App\Http\Controllers\ItemController@delete')->name('deleteItem');

Route::get('/customers/{id}/delete', 'App\Http\Controllers\CustomerController@delete')->name('deleteCustomer');

Route::get('items/{id}/edit', 'App\Http\Controllers\ItemController@edit')->name('editItem');

Route::get('customers/{id}/edit', 'App\Http\Controllers\CustomerController@edit')->name('editCustomer');

Route::patch('/items/{id}/update', 'App\Http\Controllers\ItemController@update')->name('updateItem');

Route::patch('/customers/{id}/update', 'App\Http\Controllers\CustomerController@update')->name('updateCustomer');

Route::get('/search_customer/{id}', 'App\Http\Controllers\MainTableController@searchCustomer')->name('searchCustomer');

Route::get('/search_item/{id}', 'App\Http\Controllers\MainTableController@searchItem')->name('searchItem');
