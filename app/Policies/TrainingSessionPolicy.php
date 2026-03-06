<?php

namespace App\Policies;

use App\Models\TrainingSession;
use App\Models\User;

class TrainingSessionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TrainingSession $trainingSession): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, TrainingSession $trainingSession): bool
    {
        return true;
    }

    public function delete(User $user, TrainingSession $trainingSession): bool
    {
        return true;
    }

    public function manageAttendance(User $user, TrainingSession $trainingSession): bool
    {
        return true;
    }
}
