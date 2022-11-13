<?php

use Illuminate\Support\Facades\Route;

use App\Actions\Api\Apps\DeleteAppInfo;
use App\Actions\Api\Apps\GetAppInfo;
use App\Actions\Api\Apps\StoreAppInfo;

use App\Actions\Api\AppStoreConnect\CreateBundleId;

use App\Actions\Api\Jenkins\Post\AbortJob;
use App\Actions\Api\Jenkins\Post\BuildJob;
use App\Actions\Api\Jenkins\Post\ScanOrganization;

use App\Actions\Dashboard\CreateAppForm;
use App\Actions\Dashboard\GetIndexForm;
use App\Actions\Dashboard\Workspace\GetWorkspaceForm;
use App\Actions\Dashboard\Workspace\GetJoinWorkspaceForm;
use App\Actions\Dashboard\AppStoreConnect\CreateBundleIdForm;

use App\Actions\Workspace\CreateWorkspaceApiKey;
use App\Actions\Workspace\JoinWorkspace;
use App\Actions\Workspace\StoreWorkspace;

Route::middleware(['auth', 'verified'])->group(function () {

    //////////////////////////
    //// index route
    //////////////////////////
    Route::get('/dashboard', GetIndexForm::class)->name('index');

    ////////////////////////////////
    //// workspace routes
    ////////////////////////////////
    Route::get('/dashboard/workspace-settings', GetWorkspaceForm::class)
        ->name('workspace_settings')
        ->middleware('permission:view workspace');

    Route::post('/dashboard/workspace-settings', StoreWorkspace::class)
        ->middleware('permission:create workspace|update workspace');

    Route::post('/dashboard/create-workspace-api-key', CreateWorkspaceApiKey::class)
        ->middleware('permission:update workspace');

    Route::get('/dashboard/workspace-join', GetJoinWorkspaceForm::class)
        ->name('workspace_join')
        ->middleware('permission:join workspace');

    Route::post('/dashboard/workspace-join', JoinWorkspace::class)
        ->middleware('permission:join workspace');

    //////////////////////////////
    //// app info routes
    //////////////////////////////
    Route::get('/dashboard/add-app-info', CreateAppForm::class)
        ->name('add_app_info')
        ->middleware('permission:create app');

    Route::post('/dashboard/store-app-info', StoreAppInfo::class)
        ->name('store_app_info')
        ->middleware('permission:update app');

    Route::get('/dashboard/update-app-info', GetAppInfo::class)
        ->name('get_app_info')
        ->middleware('permission:update app');

    Route::post('/dashboard/update-app-info', StoreAppInfo::class)
        ->name('update_app_info')
        ->middleware('permission:update app');

    Route::post('/dashboard/delete-app-info', DeleteAppInfo::class)
        ->name('delete_app_info')
        ->middleware('permission:delete app');

    ////////////////////////////
    //// jenkins routes
    ///////////////////////////
    Route::post('/dashboard/build-app', BuildJob::class)
        ->middleware('permission:build job');

    Route::get('/dashboard/stop-job', AbortJob::class)
        ->middleware('permission:abort job');

    Route::get('/dashboard/workspace/scan-jobs', ScanOrganization::class)
        ->middleware('permission:scan jobs')
        ->name('scan-workspace-jobs');

    //////////////////////////////////
    //// app store connect routes
    /////////////////////////////////
    Route::get('/dashboard/create-bundle', CreateBundleIdForm::class)
        ->name('create_bundle')
        ->middleware('permission:create bundle');

    Route::post('/dashboard/store-bundle', CreateBundleId::class)
        ->middleware('permission:create bundle');
});
