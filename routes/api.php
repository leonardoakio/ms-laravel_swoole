<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Utils\DocumentationController;
use App\Http\Controllers\Utils\HealthHandler;

// Health Check
Route::get("/health", [HealthHandler::class, "health"]);
Route::get("/liveness", [HealthHandler::class, "liveness"]);

// Documentation routes
Route::group(["prefix" => "documentation"], function () {
    Route::get("/", [DocumentationController::class, "show"]);
    Route::get("/v1.yaml", [DocumentationController::class, "yaml"]);
    Route::get("/v2.yaml", [DocumentationController::class, "yamlV2"]);
});
