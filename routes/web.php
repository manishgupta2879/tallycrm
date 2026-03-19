<?php

use App\Http\Controllers\AdditionalOpportunityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DistributorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management Routes
    Route::resource('users', UserController::class);
    // Role Management Routes
    Route::resource('roles', RoleController::class);
    // Category Management Routes
    Route::resource('categories', CategoryController::class);
    Route::resource('additional-opportunities', AdditionalOpportunityController::class);
    // Company Management Routes
    Route::resource('companies', CompanyController::class);
    Route::get('companies/{company}/decrypt-urls', [CompanyController::class, 'decryptUrls'])->name('companies.decrypt-urls');
    // Distributor Management Routes — explicit routes MUST be registered before resource() to avoid {distributor} wildcard conflicts
    Route::get('/distributors/get-company-details/{pid}', [DistributorController::class, 'getCompanyDetails'])->name('distributors.getCompanyDetails');
    Route::get('/distributors/geo/regions/{countryPid}', [DistributorController::class, 'getRegions'])->name('distributors.geo.regions');
    Route::get('/distributors/geo/states/{regionPid}', [DistributorController::class, 'getStates'])->name('distributors.geo.states');
    Route::get('/distributors/geo/cities/{statePid}', [DistributorController::class, 'getCities'])->name('distributors.geo.cities');
    Route::resource('distributors', DistributorController::class);


    // User Log Routes
    Route::get('/user-logs', [\App\Http\Controllers\UserLogController::class, 'index'])->name('user-logs.index');
    Route::get('/user-logs/{userLog}', [\App\Http\Controllers\UserLogController::class, 'show'])->name('user-logs.show');
});

require __DIR__ . '/auth.php';

