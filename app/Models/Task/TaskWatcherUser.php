<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskWatcherUser extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'task_watcher_user';

    /**
    * This will want to contain everything that is fillable inside the database, if the element that should be fillable
    * is not inside the array, the element will not be insertable and will always enter as a null value, or error
    * if the element is a non-nullable value
    *
    * @var array
    */
    protected $fillable = [
        'task_id',
        'user_id',
    ];

    /**
    * These are the casts that the values in the database are going to come into the system as. All elements against
    * this model that is stored within the database are going to want to have their default defined cast.
    *
    * @var array
    */
    protected $casts = [
        'task_id'    => 'int',
        'user_id'    => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
    * Each task watcher user will come equipped with a relationship to the user, so we can instantly check which user
    * it is that is currently attached. this gives the system ease of access to the user when we are showing the task
    * watcher users...
    *
    * @return BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'user_id', 'id');
    }

    /**
    * Each task watcher user will come equipped with a relationship to the task, so we can instantly check which task
    * it is that is currently attached. This gives the system ease of access to the task when we are showing the task
    * watcher users.
    *
    * @return BelongsTo
    */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
