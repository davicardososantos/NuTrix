<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WaterConsumption;

class WaterConsumptionPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WaterConsumption $waterConsumption): bool
    {
        return $user->id === $waterConsumption->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WaterConsumption $waterConsumption): bool
    {
        return $user->id === $waterConsumption->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WaterConsumption $waterConsumption): bool
    {
        return $user->id === $waterConsumption->user_id;
    }
}
