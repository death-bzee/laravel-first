<?php

namespace App\Policies\Survey;

use App\Models\User;
use App\Models\Survey\SurveyAssignment;
use Illuminate\Auth\Access\HandlesAuthorization;

class SurveyAssignmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_survey::survey::assignment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SurveyAssignment $surveyAssignment): bool
    {
        return $user->can('view_survey::survey::assignment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_survey::survey::assignment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SurveyAssignment $surveyAssignment): bool
    {
        return $user->can('update_survey::survey::assignment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SurveyAssignment $surveyAssignment): bool
    {
        return $user->can('delete_survey::survey::assignment');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_survey::survey::assignment');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, SurveyAssignment $surveyAssignment): bool
    {
        return $user->can('force_delete_survey::survey::assignment');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_survey::survey::assignment');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, SurveyAssignment $surveyAssignment): bool
    {
        return $user->can('restore_survey::survey::assignment');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_survey::survey::assignment');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, SurveyAssignment $surveyAssignment): bool
    {
        return $user->can('replicate_survey::survey::assignment');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_survey::survey::assignment');
    }
}
