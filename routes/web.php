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

Route::get('/', 'App\Http\Controllers\MainTableController@index')->name('Home');

Route::get('/items', 'App\Http\Controllers\ItemController@index')->name('Items');

Route::get('/customers', 'App\Http\Controllers\CustomerController@index')->name('Customers');

Route::get('/create', 'App\Http\Controllers\MainTableController@create')->name('Entry.create');

Route::get('/items/create', 'App\Http\Controllers\ItemController@create')->name('Item.create');

Route::get('/customers/create', 'App\Http\Controllers\CustomerController@create')->name('Customer.create');

Route::post('/store', 'App\Http\Controllers\MainTableController@store')->name('Entry.store');

Route::post('/items/store', 'App\Http\Controllers\ItemController@store')->name('Item.store');

Route::post('/customers/store', 'App\Http\Controllers\CustomerController@store')->name('Customer.store');

Route::get('/{id}/delete', 'App\Http\Controllers\MainTableController@delete')->name('Entry.delete');

Route::get('/items/{id}/delete', 'App\Http\Controllers\ItemController@delete')->name('Item.delete');

Route::get('/customers/{id}/delete', 'App\Http\Controllers\CustomerController@delete')->name('Customer.delete');

Route::get('/items/{id}/edit', 'App\Http\Controllers\ItemController@edit')->name('Item.edit');

Route::get('/customers/{id}/edit', 'App\Http\Controllers\CustomerController@edit')->name('Customer.edit');

Route::patch('/items/{id}/update', 'App\Http\Controllers\ItemController@update')->name('Item.update');

Route::patch('/customers/{id}/update', 'App\Http\Controllers\CustomerController@update')->name('Customer.update');

Route::get('/search_customer/{id}', 'App\Http\Controllers\MainTableController@searchCustomer')->name('Customer.search');

Route::get('/search_item/{id}', 'App\Http\Controllers\MainTableController@searchItem')->name('Item.search');

Route::post('/export', 'App\Http\Controllers\MainTableController@export')->name('Entry.export');
