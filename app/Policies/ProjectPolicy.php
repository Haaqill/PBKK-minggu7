<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['mahasiswa', 'dosen', 'asdos'], true);
    }

    public function view(User $user, Project $project): bool
    {
        return $this->isAdministrator($user) || $project->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Project $project): bool
    {
        return $this->view($user, $project);
    }

    public function delete(User $user, Project $project): bool
    {
        return $this->view($user, $project);
    }

    public function restore(User $user, Project $project): bool
    {
        return $this->view($user, $project);
    }

    public function bulkDeleteAny(User $user): bool
    {
        return $this->isAdministrator($user);
    }

    private function isAdministrator(User $user): bool
    {
        return in_array($user->role, ['dosen', 'asdos'], true);
    }
}
