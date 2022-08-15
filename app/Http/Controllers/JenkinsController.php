<?php

namespace App\Http\Controllers;

use App\Models\AppInfo;

use App\Http\Requests\AppInfo\GetAppInfoRequest;
use App\Http\Requests\Jenkins\BuildRequest;
use App\Http\Requests\Jenkins\StopJobRequest;

use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

class JenkinsController extends Controller
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('jenkins.host').'/job/'.config('jenkins.ws');
    }

    public function GetJobList() : JsonResponse
    {
        $jobResponse = $this->GetApiResponse('/api/json');
        $jobResponse->jenkins_data = collect($jobResponse->jenkins_data?->jobs)->pluck('name');

        return response()->json($jobResponse);
    }

    public function GetJob(GetAppInfoRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));

        $jobResponse = $this->GetApiResponse("/job/{$app->project_name}/api/json");
        $jobResponse->jenkins_data = collect($jobResponse->jenkins_data)->only(['name', 'url']);

        return response()->json($jobResponse);
    }

    public function GetJobBuilds(GetAppInfoRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));

        $jobResponse = $this->GetApiResponse("/job/{$app->project_name}/job/master/api/json");
        $builds = collect($jobResponse->jenkins_data?->builds);

        // add nextBuildNumber value to build list for detailed info for job parametrization.
        if (count($builds) == 0)
        {
            $builds = $builds->push(
                collect([
                    '_class' => 'org.jenkinsci.plugins.workflow.job.WorkflowRu',
                    'number' => $jobResponse->jenkins_data->nextBuildNumber,
                    'url' => ''
                ])
            );
        }

        $jobResponse->jenkins_data = $builds;

        return response()->json($jobResponse);
    }

    public function GetJobLastBuild(GetAppInfoRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->id);

        $jobResponse = $this->GetApiResponse("/job/{$app->project_name}/job/master/wfapi/runs");
        $builds = collect($jobResponse->jenkins_data);
        $lastBuild = $builds->first();

        if ($lastBuild)
        {
            $lastBuildDetails = $this->GetApiResponse("/job/{$app->project_name}/job/master/{$lastBuild->id}/api/json");

            // app platform
            $jobHasParameters = isset($lastBuildDetails->jenkins_data->actions[0]->parameters);
            $buildPlatform = ($jobHasParameters) ? $lastBuildDetails->jenkins_data->actions[0]?->parameters[1]?->value : 'Appstore';
            $lastBuild->build_platform = $buildPlatform;

            // add commit history
            $changeSets = isset($lastBuildDetails->jenkins_data->changeSets[0])
                ? collect($lastBuildDetails->jenkins_data->changeSets[0]->items)->pluck('msg')->reverse()->take(5)->values()
                : collect();
            $lastBuild->change_sets = $changeSets;

            // if job is running, calculate avarage duration
            if ($lastBuild->status == 'IN_PROGRESS') {
                $lastBuild->estimated_duration = $builds->avg('durationMillis');
            }

            // add job build detail
            $jobStages = collect($lastBuild->stages);

            $jobStage = $jobStages->whereIn('status', ['FAILED', 'ABORTED'])?->first()?->name ?? $jobStages->last()?->name;
            $jobStageDetail = $jobStages->whereIn('status', ['FAILED', 'ABORTED'])?->first()?->error?->message ?? '';

            $lastBuild->stop_details =  collect([
                'stage' => $jobStage,
                'output' => $jobStageDetail
            ]);
        }

        $jobResponse->jenkins_data = $lastBuild;

        return response()->json($jobResponse);
    }

    public function BuildJob(BuildRequest $request) : JsonResponse
    {
        $validated = $request->validated();

        $app = AppInfo::find($validated['id']);

        $jobResponse = $this->GetJobBuilds($request)->getData();
        $firstBuild = $jobResponse->jenkins_data[0];

        // job exist but doesn't parameterized
        if ($firstBuild->number == 1 && empty($firstBuild->url))
        {
            Artisan::call("jenkins:default-trigger {$validated['id']}");
            return response()->json(['status' => "Project: {$app->project_name} building for first time. This build gonna be aborted by Jenkins!"]);
        }

        $validated['storeCustomVersion'] ??= 'false';
        $validated['storeBuildNumber'] = ($validated['storeCustomVersion'] == 'true') ? $validated['storeBuildNumber'] : 0;

        Artisan::call("jenkins:trigger {$validated['id']} master false {$validated['platform']} {$validated['storeVersion']} {$validated['storeCustomVersion']} {$validated['storeBuildNumber']}");
        return response()->json(['status' => "Project: <b>{$app->project_name}</b> building for <b>{$validated['platform']}</b>..."]);
    }

    public function StopJob(StopJobRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));
        $url = "/job/{$app->project_name}/job/master/{$request->validated('build_number')}/stop";

        return response()->json([
            'status' => Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($this->baseUrl . $url)->status()
        ]);
    }

    private function GetApiResponse(string $url) : mixed
    {
        return $this->TryJenkinsRequest($url)->getData();
    }

    private function TryJenkinsRequest(string $url) : JsonResponse
    {
        try
        {
            $jenkinsResponse = $this->GetJenkinsApi($this->baseUrl . $url);
        }
        catch (\Exception $exception)
        {
            return response()->json($jenkinsResponse);
        }

        return response()->json($jenkinsResponse);
    }

    private function GetJenkinsApi(string $url)
    {
        $request = Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(20)
            ->connectTimeout(8)
            ->get($url);

        if ($request->header('Ngrok-Error-Code'))
        {
            return [
                'jenkins_status' => 3200,
                'jenkins_data' => null
            ];
        }

        return [
            'jenkins_status' => $request->status(),
            'jenkins_data' => json_decode($request)
        ];
    }
}
