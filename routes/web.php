<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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
Auth::routes();

Route::get('/login-with-token', 'AuthController@loginWithToken')->middleware('token_auth');

Route::get('/logout', function (Request $request) {
    Auth::logout();
    
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    $cookie = cookie('shared_session_cookie', '', -1, '/', '.localhost', false, true);
    
    return redirect('http://localhost/sourcing_plan/public/login')->withCookie($cookie);
})->name('logout');

Route::group(['middleware' => 'auth'], function () {


    Route::get('/roles','RoleController@index');

    Route::get('/home','HomeController@index');
    Route::get('/cott_summary','SummaryController@index');
    Route::get('/spi_summary','SummaryController@index');
    Route::get('/summary_suppliers','SummaryController@summary_suppliers');
    Route::post('/supplier_summary_setup','SummaryController@supplier_summary_setup');


    // Route::get('/home','UserController@index');

    // Route::get('/users','UserController@index');
    // Route::post('new_user', 'UserController@store');
    
    // ROLES
    Route::get('/roles','RoleController@index');
    Route::post('new_role', 'RoleController@store');
});
