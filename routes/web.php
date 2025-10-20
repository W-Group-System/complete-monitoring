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

Route::get('/login-with-token', 'AuthController@loginWithToken');

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

    Route::middleware(['can:access complete monitoring'])->group(function(){
        Route::get('/home','HomeController@index');
        Route::get('/cott_summary','SummaryController@index');
        Route::get('/spi_summary','SummaryController@index');
        Route::get('/summary_suppliers','SummaryController@summary_suppliers');
        Route::delete('/deleteSetup/{id}','SummaryController@deleteSupplier');
        Route::post('/supplier_summary_setup','SummaryController@supplier_summary_setup');
        Route::post('/supplier_summary_setup/edit/{id}','SummaryController@supplier_summary_edit');

        Route::get('/ccc_summary_suppliers','SummaryController@ccc_summary_suppliers');
        Route::delete('/deleteCccSetup/{id}','SummaryController@deleteCccSupplier');
        Route::post('/ccc_supplier_summary_setup','SummaryController@ccc_supplier_summary_setup');
        Route::post('/ccc_supplier_summary_setup/edit/{id}','SummaryController@ccc_supplier_summary_edit');
    });

    Route::middleware(['can:access quality'])->group(function(){
        Route::get('/quality','QualityController@index');
        Route::post('/quality/edit/{id}','QualityController@quality_edit');
        Route::get('/returned_quality', 'QualityController@returnedQuality')->name('returned_quality');
        Route::get('/approved_quality', 'QualityController@approvedQuality')->name('approved_quality');
        Route::get('/for_approval', 'QualityController@approvalQuality')->name('quality_approval');
        Route::post('/ApproveQuality/{id}', 'QualityController@ApproveQuality');
    });
    Route::middleware(['can:access ccc quality'])->group(function(){
        Route::get('/cccQuality','QualityController@cccIndex');
        Route::post('/ccc_quality/edit/{id}','QualityController@ccc_quality_edit');
        Route::get('/ccc_returned_quality', 'QualityController@returnedQuality')->name('ccc_returned_quality');
        Route::get('/ccc_approved_quality', 'QualityController@approvedQuality')->name('ccc_approved_quality');
        Route::get('/ccc_for_approval', 'QualityController@approvalQuality')->name('ccc_quality_approval');
    });
    Route::middleware(['can:access quality approval'])->group(function(){
        Route::get('/quality_approval', 'QualityController@quality_approval');
        Route::post('/DisapproveQuality/{id}', 'QualityController@DisapproveQuality');
        Route::post('/ApproveAllQuality','QualityController@approveAll');
        Route::post('/ApproveQuality/{id}', 'QualityController@ApproveQuality');

    });
    Route::middleware(['can:access ccc quality approval'])->group(function(){
        Route::get('/ccc_quality_approval', 'QualityController@ccc_quality_approval');
        Route::post('/CccDisapproveQuality/{id}', 'QualityController@CccDisapproveQuality');
        Route::post('/ApproveAllQuality','QualityController@approveAll');
        Route::post('/CccApproveQuality/{id}', 'QualityController@CccApproveQuality');
    });
    Route::middleware(['can:access quality report'])->group(function(){
        Route::get('/qualityReport','QualityController@qualityReport');
    });
    Route::middleware(['can:access quality approval setup'])->group(function(){
        Route::get('/quality_approval_setup','QualityApproverSetupController@index');
        Route::post('/new_approver_setup', 'QualityApproverSetupController@store');
        Route::post('activate-approver/{id}', 'QualityApproverSetupController@activate');
        Route::post('deactivate-approver/{id}', 'QualityApproverSetupController@deactivate');
    });
    Route::get('/print_quality_report/{id}', 'QualityController@print');
    Route::get('/ccc_print_quality_report/{id}', 'QualityController@cccPrint');


    // Route::get('/home','UserController@index');

    // Route::get('/users','UserController@index');
    // Route::post('new_user', 'UserController@store');
    
    // ROLES
    Route::get('/roles','RoleController@index');
    Route::post('new_role', 'RoleController@store');
});
