<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Jantinnerezo\LivewireAlert\LivewireAlert;

use App\Models\AppInfo;
use App\Actions\Api\Ftp\CreateFBAppAds;

class FbAppAds extends Component
{
    use LivewireAlert;
    use AuthorizesRequests;

    public AppInfo $appInfo;

    public function integrate()
    {
        $this->authorize('update app');

        $response = CreateFBAppAds::run($this->appInfo);

        $this->alert(
            $response['success'] ? 'success' : 'error',
            $response['message']
        );
    }

    public function render() : View
    {
        return view('livewire.app-fb-adds-btn');
    }
}
