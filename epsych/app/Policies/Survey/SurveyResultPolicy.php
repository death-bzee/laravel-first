<?php

namespace App\Policies\Survey;

use App\Models\User;
use App\Models\Survey\SurveyResult;
use Illuminate\Auth\Access\HandlesAuthorization;

class SurveyResultPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_survey::survey::result');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SurveyResult $surveyResult): bool
    {
        return $user->can('view_survey::survey::result');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_survey::survey::result');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SurveyResult $surveyResult): bool
    {
        return $user->can('update_survey::survey::result');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SurveyResult $surveyResult): bool
    {
        return $user->can('delete_survey::survey::result');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_survey::survey::result');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, SurveyResult $surveyResult): bool
    {
        return $user->can('force_delete_survey::survey::result');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_survey::survey::result');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, SurveyResult $surveyResult): bool
    {
        return $user->can('restore_survey::survey::result');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_survey::survey::result');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, SurveyResult $surveyResult): bool
    {
        return $user->can('replicate_survey::survey::result');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_survey::survey::result');
    }
}
