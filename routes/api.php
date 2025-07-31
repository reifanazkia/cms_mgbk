<?php

use App\Http\Controllers\Api\ApiAgendaController;
use App\Http\Controllers\Api\ApiHowsController;
use App\Http\Controllers\Api\ApiAboutUsController;
use App\Http\Controllers\Api\ApiAnggotaController;
use App\Http\Controllers\Api\ApiCareerController;
use App\Http\Controllers\Api\ApiKegiatanController;
use App\Http\Controllers\Api\ApiOurblogController;
use App\Http\Controllers\Api\ApiProductsController;
use App\Http\Controllers\Api\ApiSliderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/hows', [ApiHowsController::class, 'index']);
Route::get('/hows/{id}', [ApiHowsController::class, 'show']);
Route::get('/agendas', [ApiAgendaController::class, 'index']);
Route::get('/agendas/{id}', [ApiAgendaController::class, 'show']);
Route::get('/about-us', [ApiAboutUsController::class, 'index']);
Route::get('/about-us/{id}', [ApiAboutUsController::class, 'show']);
Route::get('/career', [ApiCareerController::class, 'index']);
Route::get('/career/{id}', [ApiCareerController::class, 'show']);
Route::get('/ourblog', [ApiOurblogController::class, 'index']);
Route::get('/ourblog/{id}', [ApiOurblogController::class, 'show']);
Route::get('/product', [ApiProductsController::class, 'index']);
Route::get('/product/{id}', [ApiProductsController::class, 'show']);
Route::get('/slider', [ApiSliderController::class, 'index']);
Route::get('/kegiatan', [ApiKegiatanController::class, 'index']);
Route::get('/kegiatan/{id}', [ApiKegiatanController::class, 'show']);
Route::get('/anggota', [ApiAnggotaController::class, 'index']);
Route::get('/anggota/{id}', [ApiAnggotaController::class, 'show']);
