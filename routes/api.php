<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/phpinfo', function () {
    ob_start();
    phpinfo();
    $phpInfo = ob_get_clean();

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: text/html; charset=UTF-8");

    return $phpInfo;
});
