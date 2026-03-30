<?php

use App\Http\Controllers\Api\TallyDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | All routes are prefixed with /api automatically (configured in | bootstrap/app.php). These routes are stateless and secured via | the ValidateApiKey middleware (X-API-Key header). | | Usage: |   POST /api/tally/master-data-upload |   Headers: Content-Type: application/json |             X-API-Key: <configured key> | */

Route::middleware(['api_key'])->prefix('tally')->name('api.tally.')->group(function () {

    // Best practice URL as requested: api/tally/master-data-upload
    Route::match (['get', 'post'], 'master-data-upload', [TallyDataController::class , 'handle'])
        ->name('master-data-upload');

});
