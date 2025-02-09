<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PartController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// car routes
Route::get('/dashboard/cars', function () {
    return Inertia::render('Cars');
})->middleware(['auth', 'verified'])->name('dashboard.cars');

Route::get('/car/{id}', [CarController::class, 'car'])
    ->middleware(['auth', 'verified'])
    ->name('car.byId');

Route::get('/cars/search/{search}', [CarController::class, 'search'])
    ->middleware(['auth', 'verified'])
    ->name('cars.search');

Route::get('/dashboard/cars/add', function () {
    return Inertia::render('Cars/AddCar');
})->middleware(['auth', 'verified'])->name('dashboard.cars.add');

Route::post('/dashboard/cars', [CarController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.cars.store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/cars/{id}', [CarController::class, 'show'])
    ->name('dashboard.cars.show');
    Route::delete('/dashboard/cars/{id}', [CarController::class, 'destroy'])
    ->name('dashboard.cars.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/cars/{id}/edit', [CarController::class, 'edit'])
        ->name('dashboard.cars.edit');
    Route::patch('/dashboard/cars/{id}/edit', [CarController::class, 'update'])
        ->name('dashboard.cars.update');
});

// end car routes


// part routes
Route::get('/dashboard/parts', function () {
    return Inertia::render('Parts');
})->middleware(['auth', 'verified'])->name('dashboard.parts');

Route::get('/parts/search/{search}', [PartController::class, 'search'])
    ->middleware(['auth', 'verified'])
    ->name('parts.search');

Route::get('/dashboard/parts/add/{car_id}', function ($car_id) {
    return Inertia::render('Parts/AddPart', [
        'id' => $car_id,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard.parts.add');

Route::post('/dashboard/parts', [PartController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.parts.store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/parts/{id}', [PartController::class, 'show'])
    ->name('dashboard.parts.show');
    Route::delete('/dashboard/parts/{id}', [PartController::class, 'destroy'])
    ->name('dashboard.parts.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/parts/{id}/edit', [PartController::class, 'edit'])
        ->name('dashboard.parts.edit');
    Route::patch('/dashboard/parts/{id}/edit', [PartController::class, 'update'])
        ->name('dashboard.parts.update');
});

// end part routes










Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
