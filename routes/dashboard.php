<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

use App\Actions\Dashboard\GetIndexForm;
use App\Actions\Dashboard\StoreAppForm;
use App\Actions\Dashboard\CreateAppForm;
use App\Actions\Dashboard\UpdateApp;
use App\Actions\Dashboard\SelectApp;
use App\Actions\Dashboard\DeleteApp;

use App\Actions\Workspace\GetWorkspaceForm;
use App\Actions\Workspace\StoreWorkspaceForm;

use App\Actions\AppStoreConnect\StoreBundleId;

use App\Actions\Jenkins\StopJob;

Route::controller(DashboardController::class)->middleware(['auth', 'verified'])->group(function () {

    //// index route
    Route::get('/dashboard', GetIndexForm::class)->name('index');

    //// workspace routes
    // create or update workspace
    Route::get('/dashboard/workspace-settings', GetWorkspaceForm::class)
        ->name('workspace_settings')
        ->middleware('permission:view workspace');

    Route::post('/dashboard/workspace-settings', StoreWorkspaceForm::class)
        ->middleware('permission:create workspace|update workspace');

    // join workspace
    Route::get('/dashboard/workspace-join', 'GetJoinWorkspaceForm')
        ->name('workspace_join')
        ->middleware('permission:join workspace');

    Route::post('/dashboard/workspace-join', 'PostJoinWorkspaceForm')
        ->middleware('permission:join workspace');

    //// app info routes
    Route::get('/dashboard/add-app-info', CreateAppForm::class)
        ->name('add_app_info')
        ->middleware('permission:create app');

    Route::post('/dashboard/store-app-info', StoreAppForm::class)
        ->name('store_app_info')
        ->middleware('permission:update app');

    Route::get('/dashboard/update-app-info', SelectApp::class)
        ->name('get_app_info')
        ->middleware('permission:update app');

    Route::post('/dashboard/update-app-info', UpdateApp::class)
        ->name('update_app_info')
        ->middleware('permission:update app');

    Route::post('/dashboard/delete-app-info', DeleteApp::class)
        ->name('delete_app_info')
        ->middleware('permission:delete app');

    //// jenkins routes
    Route::post('/dashboard/build-app', 'BuildApp')->middleware('permission:build job');
    Route::get('/dashboard/stop-job', StopJob::class)->middleware('permission:abort job');
    Route::get('/dashboard/scan-repo', 'ScanRepo')->middleware('permission:scan jobs');

    //// app store connect routes
    Route::get('/dashboard/create-bundle', 'CreateBundleForm')
        ->name('create_bundle')
        ->middleware('permission:create bundle');

    Route::post('/dashboard/store-bundle', StoreBundleId::class);
});
