<?php

namespace App\Features;

use App\Models\User;

// only internal talus users for now...
class iOSBundleId
{
    public function resolve(User $user) : bool
    {
        return match (true)
        {
            $user->isInternal() => true,
            default => false,
        };
    }
}
