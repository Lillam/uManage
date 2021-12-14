<?php

namespace App\Policies\Task;

use App\Policies\Policy;
use App\Models\Task\Task;
use App\Models\User\User;

class TaskPolicy extends Policy
{
    /**
    * Permission check to see if the authenticated user is able to view the particular task. (this will require
    * checking if the user is a part of the project, or the reporter of the task or the current task assignee)
    * if this passes true then the user has ability to view the task in question
    *
    * @param User $self
    * @param Task $task
    * @return bool
    */
    public function viewTask(User $self, Task $task): bool
    {
        return (
            $self->id === $task->user_id ||
            $self->id === $task->assigned_user_id ||
            $self->can('ProjectPolicy@viewProject', $task->project)
        );
    }

    /**
    * Permission check to see if the authenticated user is able to edit the particular task. (this will require
    * checking if the user is a part of the project, or the reporter of the task or the current task assignee)
    * if this passes true then the user has ability to edit the task in question
    *
    * @param User $self
    * @param Task $task
    * @return bool
    */
    public function editTask(User $self, Task $task): bool
    {
        return $this->viewTask($self, $task);
    }
}