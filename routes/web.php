<?php

use App\Http\Controllers\HomeController;
use App\Http\Middlewares\AdminMiddleware;
use App\Http\Middlewares\UserMiddleware;
use Kernel\Application\Routing\Route;

return [

    Route::get('/', [HomeController::class, 'index'])
        ->middleware([
            UserMiddleware::class,
            AdminMiddleware::class,
        ])
        ->name('home'),

    Route::get('/test', [HomeController::class, 'test'])->name('test'),
    Route::post('/test/{name}', [HomeController::class, 'store'])->name('store'),
    Route::get('/123/{name}', [HomeController::class, 'test'])->name('123'),
];
