<?php

use App\User;
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

Route::get('/login-with-token', function () {
    return response()->view('login-token-redirect');
});
// Route::get('/menu', function (Request $request) {
//     if (!Auth::check() && $request->has('token')) {
//         $user = User::where('api_token', $request->token)->first();

//         if ($user) {
//             Auth::login($user);
//         }
//     }

//     if (!Auth::check()) {
//         return redirect('/login');
//     }

//     $token = Auth::user()->api_token;

//     $system1Url = app()->environment('local')
//         ? 'http://localhost/sourcing_plan/public/menu'
//         : 'https://sourcing-plan.wsystem.online/menu';

//         return redirect()->away($system1Url);
// })->name('system2.menu');

Route::get('/login-with-token-check', 'AuthController@loginWithToken');

Route::get('/logout', function (Request $request) {
    Auth::logout();
    
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    $cookie = cookie('shared_session_cookie', '', -1, '/', '.localhost', false, true);
    
    // $redirectUrl = app()->environment('local')
    //     ? 'http://localhost/sourcing_plan/public/login'
    //     : url('/login');
    $redirectUrl = app()->environment('local')
        ? 'http://localhost/sourcing_plan/public/login'
        : url('https://sourcing-plan.wsystem.online/login');

        return redirect($redirectUrl)->withCookie($cookie);
})->name('logout');

Route::group(['middleware' => 'auth'], function () {


    Route::get('/roles','RoleController@index');

    Route::get('/home','HomeController@index');
    Route::get('/cott_summary','SummaryController@index');
    Route::get('/spi_summary','SummaryController@index');
    Route::get('/summary_suppliers','SummaryController@summary_suppliers');
    Route::post('/supplier_summary_setup','SummaryController@supplier_summary_setup');
    Route::post('/supplier_summary_setup/edit/{id}','SummaryController@supplier_summary_edit');


    // Route::get('/home','UserController@index');

    // Route::get('/users','UserController@index');
    // Route::post('new_user', 'UserController@store');
    
    // ROLES
    Route::get('/roles','RoleController@index');
    Route::post('new_role', 'RoleController@store');
});
