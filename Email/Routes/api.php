<?php

use Illuminate\Http\Request;
use Modules\Email\Http\Controllers\EmailController;

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

Route::middleware('auth:api')->get('/email', function (Request $request) {
    return $request->user();
});

Route::prefix('/mail')->group(function(){
    Route::post('/send', [EmailController::class, 'send']);
});