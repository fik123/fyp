<?php

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
    return view('welcome2');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('table','TableController');
Route::resource('menu','MenuController');
Route::get('order','OrderController@index')->name('order.index');
Route::post('order/{tableno}','OrderController@store')->name('order.store');
Route::get('order/create/{tableno}','OrderController@create')->name('order.create');//s
Route::get('ordermass/{tableno}','OrderController@masscreate')->name('order.mass');//s
Route::put('order/{order}','OrderController@update')->name('order.update');
Route::put('bulkorder/{order}','OrderController@bulkupdate')->name('order.bulkupdate');
Route::delete('order/{order}','OrderController@destroy')->name('order.destroy');
Route::get('order/{order}/{tableno}','OrderController@show')->name('order.show');//s
Route::resource('mcook','McookController');
// Route::put('customermcook/{mcook}','McookController@joinmass')->name('mcook.joinmass');

// admin routing
Route::get('admintable','AdminController@alltable')->name('admin.table');
Route::get('adminmenu','AdminController@allmenu')->name('admin.menu');
Route::get('adminole','AdminController@rolesetting')->name('admin.role');
Route::get('admingenqr/{table}','AdminController@generateqr')->name('admin.genqr');

// customer routing
Route::get('customer/main','CustomerController@main')->name('customer.main');
Route::get('customer/option','CustomerController@option')->name('customer.option');
Route::get('customer/{orderno}','CustomerController@orders')->name('customer.order');

// kitchen routing
Route::get('kitchen/main','KitchenController@main')->name('kitchen.main');
Route::get('kitchen/waiter','KitchenController@waiter')->name('kitchen.waiter');
Route::get('kitchen/cashier','KitchenController@cashier')->name('kitchen.cashier');
Route::get('ordercooking','OrderController@indexcooking')->name('order.cooking');
Route::get('orderwaiter','OrderController@waiter')->name('order.waiter');

// cashier routing
Route::get('kitchen/cashier/{table}','PaymentController@tablecashout')->name('payment.tablecashout');
Route::get('cashier/{order}','PaymentController@cashierorder')->name('payment.cashierorder');
Route::get('cashierselected/{order}','PaymentController@cashierselected')->name('payment.cashierselected');
Route::get('cashierall/{order}','PaymentController@cashierall')->name('payment.cashierall');

Route::get('searchorderno','OrderController@search')->name('search.orderno');