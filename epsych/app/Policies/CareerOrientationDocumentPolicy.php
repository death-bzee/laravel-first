<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CareerOrientationDocument;
use Illuminate\Auth\Access\HandlesAuthorization;

class CareerOrientationDocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_career::orientation::document');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CareerOrientationDocument $careerOrientationDocument): bool
    {
        return $user->can('view_career::orientation::document');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_career::orientation::document');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CareerOrientationDocument $careerOrientationDocument): bool
    {
        return $user->can('update_career::orientation::document');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CareerOrientationDocument $careerOrientationDocument): bool
    {
        return $user->can('delete_career::orientation::document');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_career::orientation::document');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, CareerOrientationDocument $careerOrientationDocument): bool
    {
        return $user->can('force_delete_career::orientation::document');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_career::orientation::document');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, CareerOrientationDocument $careerOrientationDocument): bool
    {
        return $user->can('restore_career::orientation::document');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_career::orientation::document');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, CareerOrientationDocument $careerOrientationDocument): bool
    {
        return $user->can('replicate_career::orientation::document');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_career::orientation::document');
    }
}
