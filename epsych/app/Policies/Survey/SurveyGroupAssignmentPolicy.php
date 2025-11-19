<?php

namespace App\Policies\Survey;

use App\Models\User;
use App\Models\Survey\SurveyGroupAssignment;
use Illuminate\Auth\Access\HandlesAuthorization;

class SurveyGroupAssignmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_survey::survey::group::assignment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SurveyGroupAssignment $surveyGroupAssignment): bool
    {
        return $user->can('view_survey::survey::group::assignment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_survey::survey::group::assignment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SurveyGroupAssignment $surveyGroupAssignment): bool
    {
        return $user->can('update_survey::survey::group::assignment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SurveyGroupAssignment $surveyGroupAssignment): bool
    {
        return $user->can('delete_survey::survey::group::assignment');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_survey::survey::group::assignment');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, SurveyGroupAssignment $surveyGroupAssignment): bool
    {
        return $user->can('force_delete_survey::survey::group::assignment');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_survey::survey::group::assignment');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, SurveyGroupAssignment $surveyGroupAssignment): bool
    {
        return $user->can('restore_survey::survey::group::assignment');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_survey::survey::group::assignment');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, SurveyGroupAssignment $surveyGroupAssignment): bool
    {
        return $user->can('replicate_survey::survey::group::assignment');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_survey::survey::group::assignment');
    }
}
