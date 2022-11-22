<?php

namespace App\Actions\Dashboard\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Contracts\View\View;

class GetJoinWorkspaceForm
{
    use AsAction;

    public function handle() : View
    {
        return view('workspace-join');
    }
}
