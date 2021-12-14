<?php

namespace App\Models\Traits\Relationships;

use App\Models\Task\TaskWatcherUser;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait TaskWatcherRelationship
{
    /**
    * A task can have many watchers, and this will be collecting all the watchers that are assigned to this task, this
    * table is a connector table, and doesn't have access to the direct user after calling this, thus you will need to
    * run task_watcher_user->user->something... in order to utilise the user model on this, this is just a pivot table.
    *
    * @return HasMany
    */
    public function task_watcher_users(): HasMany
    {
        return $this->hasMany(TaskWatcherUser::class, 'task_id', 'id');
    }
}