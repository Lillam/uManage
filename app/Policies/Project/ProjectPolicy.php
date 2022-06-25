<?php

namespace App\Policies\Project;

use App\Http\Controllers\Web\Project\ProjectController;
use App\Models\Project\Project;
use App\Models\User\User;
use App\Policies\Policy;

class ProjectPolicy extends Policy
{
    /**
    * @param User $self
    * @return bool
    */
    public function hasAccess(User $self): bool
    {
        return array_key_exists(
            ProjectController::class . '@_viewProjectsGet',
            $self->system_access
        );
    }

    /**
    * Permission check to see if the authenticated user is able to view the particular project. (this will require
    * checking if the user is a part of the project, or the creator of the project. if this passes true then the user
    * has ability to view the project in question.
    *
    * @param User $self
    * @param Project $project
    * @return bool
    */
    public function viewProject(User $self, Project $project): bool
    {
        return (
            $project->user_id === $self->id ||
            in_array($self->id, $project->user_contributors->pluck('user_id')->toArray())
        );
    }

    /**
    * Permission check to see if the authenticated user is able to edit the particular project. (this will require
    * checking if the user is a part of the project, or the creator of the project. if this passes true then the user
    * has ability to edit the project in question.
    *
    * @param User $self
    * @param Project $project
    * @return bool
    */
    public function editProject(User $self, Project $project): bool
    {
        return $this->viewProject($self, $project);
    }

    /**
    * Permission check to see if the authenticated user is able to delete the particular project ()this will require
    * checking to see if the user in question, is the owner of the project... and if the owner is the creator then the
    * user has the permission set in place to be able to remove the project
    *
    * @param User $self
    * @param Project $project
    * @return bool
    */
    public function deleteProject(User $self, Project $project): bool
    {
        return $project->user_id === $self->id;
    }
}