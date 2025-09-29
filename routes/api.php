<?php

use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/refresh-permissions', function (Request $request) {
    $incoming = $request->header('X-Secret-Key');
    $expected = env('SYSTEM_SYNC_KEY');

    return response()->json([
        'incoming' => $incoming,
        'expected' => $expected,
    ]);
});
