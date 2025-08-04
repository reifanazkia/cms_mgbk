<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\Api\ApiAboutUsController;
use App\Http\Controllers\Api\ApiAgendaController;
use App\Http\Controllers\Api\ApiAnggotaController;
use App\Http\Controllers\Api\ApiCareerController;
use App\Http\Controllers\Api\ApiHowsController;
use App\Http\Controllers\Api\ApiKegiatanController;
use App\Http\Controllers\Api\ApiOurblogController;
use App\Http\Controllers\Api\ApiProductsController;
use App\Http\Controllers\Api\ApiSliderController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\HowsController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\OurblogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/hows', [ApiHowsController::class, 'index']);
Route::get('/hows/{id}', [ApiHowsController::class, 'show']);
Route::get('/agendas', [ApiAgendaController::class, 'index']);
Route::get('/agendas/{id}', [ApiAgendaController::class, 'show']);
Route::get('/about-us', [ApiAboutUsController::class, 'index']);
Route::get('/about-us/{id}', [ApiAboutUsController::class, 'show']);
Route::get('/career', [ApiCareerController::class, 'index']);
Route::get('/career/{id}', [ApiCareerController::class, 'show']);
Route::get('/ourblog', [ApiOurblogController::class, 'index']);
Route::get('/ourblog/{id}', [OurblogController::class, 'show']);
Route::get('/product', [ApiProductsController::class, 'index']);
Route::get('/product/{id}', [ApiProductsController::class, 'show']);
Route::get('/slider', [ApiSliderController::class, 'index']);
Route::get('/slider/home', [ApiSliderController::class, 'showHomeSlider']);
Route::get('/kegiatan', [ApiKegiatanController::class, 'index']);
Route::get('/kegiatan/{id}', [ApiKegiatanController::class, 'show']);
Route::get('/anggota', [ApiAnggotaController::class, 'index']);

Route::middleware(['auth:api'])->group(
    function () {

        Route::prefix('hows')->name('hows.')->group(function () {
            Route::post('/', [HowsController::class, 'store'])->name('store');
            Route::put('/{id}', [HowsController::class, 'update'])->name('update');
            Route::delete('/{id}', [HowsController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('products')->name('products.')->group(function () {
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::put('/{id}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delete', [ProductController::class, 'bulkDelete'])->name('bulkDelete');
        });

        Route::prefix('about')->name('about.')->group(function () {
            Route::post('/', [AboutUsController::class, 'store'])->name('store');
            Route::put('/{id}', [AboutUsController::class, 'update'])->name('update');
            Route::delete('/{id}', [AboutUsController::class, 'destroy'])->name('destroy');
            Route::delete('/photo/{id}', [AboutUsController::class, 'deletePhoto'])->name('photo.delete');
        });

        Route::prefix('ourblogs')->name('ourblogs.')->group(function () {
            Route::post('/', [OurblogController::class, 'store'])->name('store');
            Route::put('/{id}', [OurblogController::class, 'update'])->name('update');
            Route::delete('/{id}', [OurblogController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delete', [OurblogController::class, 'bulkDelete'])->name('bulkDelete');
        });

        Route::prefix('category')->name('category.')->group(function () {
            Route::post('/store', [CategoryController::class, 'store'])->name('store');
            Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('agenda')->name('agenda.')->group(function () {
            Route::post('/', [AgendaController::class, 'store'])->name('store');
            Route::put('/{id}', [AgendaController::class, 'update'])->name('update');
            Route::delete('/{id}', [AgendaController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delete', [AgendaController::class, 'bulkDelete'])->name('bulkDelete');
        });

        Route::prefix('career')->name('career.')->group(function () {
            Route::post('/', [CareerController::class, 'store'])->name('store');
            Route::put('/{career}', [CareerController::class, 'update'])->name('update');
            Route::delete('/{id}', [CareerController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('slider')->name('slider.')->group(function () {
            Route::post('/', [SliderController::class, 'store'])->name('store');
            Route::put('/{id}', [SliderController::class, 'update'])->name('update');
            Route::delete('/{id}', [SliderController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('anggota')->name('anggota.')->group(function () {
            Route::post('/', [AnggotaController::class, 'store'])->name('store');
            Route::put('/{id}', [AnggotaController::class, 'update'])->name('update');
            Route::delete('/{id}', [AnggotaController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delete', [AnggotaController::class, 'bulkDelete'])->name('bulkDelete');
        });

        Route::prefix('kegiatan')->name('kegiatan.')->group(function () {
            Route::post('/', [KegiatanController::class, 'store'])->name('store');
            Route::put('/{id}', [KegiatanController::class, 'update'])->name('update');
            Route::delete('/{id}', [KegiatanController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delete', [KegiatanController::class, 'bulkDelete'])->name('bulkDelete');
        });
    }
);

route::post('/Login', [UserController::class, 'Login'])->name('Login');
route::post('/Logout', [UserController::class, 'logout']);
