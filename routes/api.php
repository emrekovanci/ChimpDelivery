<?php

use App\Actions\Api\AppStoreConnect\CreateToken;
use App\Actions\Api\AppStoreConnect\GetFullAppInfo;
use App\Actions\Api\AppStoreConnect\GetAppList;
use App\Actions\Api\AppStoreConnect\GetBuildList;
use App\Actions\Api\AppStoreConnect\CreateBundleId;

use App\Actions\Api\Github\GetRepositories;
use App\Actions\Api\Github\GetRepository;
use App\Actions\Api\Github\CreateRepository;

use App\Actions\Api\Packages\GetPackage;
use App\Actions\Api\Packages\GetPackages;
use App\Actions\Api\Packages\UpdatePackage;

use App\Http\Controllers\Api\AppInfoController;
use App\Http\Controllers\Api\JenkinsController;

use Illuminate\Support\Facades\Route;

///////////////////////
// apps
//////////////////////
Route::controller(AppInfoController::class)->middleware('auth:sanctum')->group(function () {

    Route::get('apps/get-app', 'GetApp');
    Route::post('apps/create-app', 'CreateApp');
    Route::post('apps/update-app', 'UpdateApp');
});

/////////////////////////
// appstore connect api
////////////////////////
Route::middleware('auth:sanctum')->group(function () {

    Route::get('appstoreconnect/get-token', CreateToken::class);
    Route::get('appstoreconnect/get-full-info', GetFullAppInfo::class);
    Route::get('appstoreconnect/get-app-list', GetAppList::class);
    Route::get('appstoreconnect/get-build-list', GetBuildList::class);

    Route::post('appstoreconnect/create-bundle', CreateBundleId::class);
});

////////////////////
// jenkins api
////////////////////
Route::controller(JenkinsController::class)->middleware('auth:sanctum')->group(function () {

    Route::get('jenkins/get-job', 'GetJob');
    Route::get('jenkins/get-job-list', 'GetJobList');
    Route::get('jenkins/get-job-builds', 'GetJobBuilds');
    Route::get('jenkins/get-job-lastbuild', 'GetJobLastBuild');

    Route::post('jenkins/build-job', 'BuildJob');
    Route::post('jenkins/stop-job', 'StopJob');
});

////////////////////////
// github api
//////////////////////
Route::middleware('auth:sanctum')->group(function () {

    Route::get('github/get-repositories', GetRepositories::class);
    Route::get('github/get-repository', GetRepository::class);
    Route::post('github/create-repository', CreateRepository::class);
});

//////////////////////////////
// packages
/////////////////////////////
Route::middleware('auth:sanctum')->group(function () {

    Route::get('packages/get-packages', GetPackages::class);
    Route::get('packages/get-package', GetPackage::class);
    Route::post('packages/update-package', UpdatePackage::class);
});
