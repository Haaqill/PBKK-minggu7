<?php

namespace App\Policies;

use App\Models\Proposal;
use App\Models\User;

class ProposalPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array(
            $user->role,
            ['dosen', 'asdos', 'mahasiswa'],
            true
        );
    }

    public function view(User $user, Proposal $proposal): bool
    {
        if (in_array($user->role, ['dosen', 'asdos'], true)) {
            return true;
        }

        return $user->role === 'mahasiswa'
            && $proposal->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'mahasiswa';
    }

    public function update(User $user, Proposal $proposal): bool
    {
        return $user->role === 'mahasiswa'
            && $proposal->user_id === $user->id
            && $proposal->status === 'menunggu';
    }

    public function delete(User $user, Proposal $proposal): bool
    {
        return $user->role === 'mahasiswa'
            && $proposal->user_id === $user->id
            && $proposal->status === 'menunggu';
    }
}