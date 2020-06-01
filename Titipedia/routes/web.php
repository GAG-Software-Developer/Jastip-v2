<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('home');
});

Route::get('/home', 'HomeController@index');
Auth::routes();

Route::get('/profile', 'HomeController@index')->name('pages/home');

//Produk
Route::get('/produk', 'ProdukController@index');
Route::get('/produk/create', 'ProdukController@create');
Route::get('/produk/{produk}', 'ProdukController@show'); // harus dibawwah, krn kalau diatas akan dibaca menampilkan produk yg idnya create
Route::post('/produk', 'ProdukController@store');
Route::delete('/produk/{produk}', 'ProdukController@destroy');
Route::get('/produk/{produk}/edit', 'ProdukController@edit');
Route::patch('/produk/{produk}', 'ProdukController@update');

//Produk Bulk Buy
Route::get('/produk-bulk-buy', 'ProdukBulkBuyController@index');
Route::get('/produk-bulk-buy/create', 'ProdukBulkBuyController@create');
Route::get('/produk-bulk-buy/{produk}', 'ProdukBulkBuyController@show'); // harus dibawwah, krn kalau diatas akan dibaca menampilkan produk yg idnya create
Route::post('/produk-bulk-buy', 'ProdukBulkBuyController@store');
Route::delete('/produk-bulk-buy/{produk}', 'ProdukBulkBuyController@destroy');
Route::get('/produk-bulk-buy/{produk}/edit', 'ProdukBulkBuyController@edit');
Route::patch('/produk-bulk-buy/{produk}', 'ProdukBulkBuyController@update');

//request
Route::get('/request', 'RequestController@index');
Route::get('/request/create', 'RequestController@create');
Route::post('/request', 'RequestController@store');
Route::delete('/request/{req}', 'RequestController@destroy');
Route::get('/request/{req}/edit', 'RequestController@edit');
Route::patch('/request/{req}', 'RequestController@update');

//User
//Route::post('/tambahsaldo', 'UserController@update');
Route::get('/Profile/{profile}/edit', 'UserController@edit');

//topup
Route::get('/topup', 'Mutasi_SaldosController@index');
Route::post('/tambahsaldo', 'MutasiSaldoController@store');
Route::post('/tariksaldo', 'MutasiSaldoController@withdraw');

//pesan
Route::get('/pesan', 'PesanController@index');
Route::get('/pesan/{pesan}', 'PesanController@chat');

//Penjualan Pre-order
Route::get('/order/{product}', 'PenjualanPreorderController@showProduk');
Route::post('/order/confirm', 'PenjualanPreorderController@store');
Route::get('/order/daftar_pembelian_preorder/{id}', 'PenjualanPreorderController@show');

//Profile
Route::get('/profile', 'UserController@index');

//RajaOngkir
Route::post('/order/get_price', 'PenjualanPreorderController@RajaOngkir');

