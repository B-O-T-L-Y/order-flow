<?php

namespace App\Policies;

use App\Models\Export;
use App\Models\User;

class ExportPolicy
{
    public function view(User $user, Export $export): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function download(User $user, Export $export): bool
    {
        return $user->is_admin;
    }
}
