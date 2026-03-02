<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WeightEntry;

class WeightEntryPolicy
{
    /**
     * Determine whether the user can view the weight entry.
     */
    public function view(User $user, WeightEntry $weightEntry): bool
    {
        return $user->id === $weightEntry->user_id;
    }

    /**
     * Determine whether the user can update the weight entry.
     */
    public function update(User $user, WeightEntry $weightEntry): bool
    {
        return $user->id === $weightEntry->user_id;
    }

    /**
     * Determine whether the user can delete the weight entry.
     */
    public function delete(User $user, WeightEntry $weightEntry): bool
    {
        return $user->id === $weightEntry->user_id;
    }
}
