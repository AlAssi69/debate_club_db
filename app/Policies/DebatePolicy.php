<?php

namespace App\Policies;

use App\Models\Debate;
use App\Models\User;

class DebatePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Debate $debate): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Debate $debate): bool
    {
        return true;
    }

    public function delete(User $user, Debate $debate): bool
    {
        return true;
    }
}
