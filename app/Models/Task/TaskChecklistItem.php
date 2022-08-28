<?php

namespace App\Models\Task;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Task\TaskLogRepository;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use App\Models\Traits\CompositeKey;

class TaskChecklistItem extends Model
{
    // use CompositeKey;

    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'task_checklist_item';

    // /**
    // * @var string[]
    // */
    // protected $primaryKey = ['project_id', 'task_id', 'user_id', 'task_checklist_id', 'id'];

    /**
    * This will contain everything that can be inserted into the table, this will be everything other than the
    * timestamp fields (created_at, updated_at). everything else that gets added will want to also be added into here
    *
    * @var array
    */
    protected $fillable = [
        //'id',
        'project_id',
        'task_id',
        'user_id',
        'task_checklist_id',
        'name',
        'order',
        'is_checked'
    ];

    /**
    * This will contain everything that the database entries have, each element in the database will want an assigned
    * data type, and it is better to cast it to its' default datatype before we start manipulating methods and checks.
    *
    * @var array
    */
    protected $casts = [
        // 'id'                => 'int',
        'project_id'        => 'int',
        'task_id'           => 'int',
        'user_id'           => 'int',
        'task_checklist_id' => 'int',
        'name'              => 'string',
        'order'             => 'int',
        'is_checked'        => 'boolean',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime'
    ];

    // /**
    // * @var bool
    // */
    // public $incrementing = false;

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
    | Logging
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with logging information around the specific model in
    | question, in this case: the TaskChecklistItem
    |
    */

    /**
    * This method is specifically for logging any change against a task checklist item, all we need to do is pass in a
    * log type, (constant of the logging type), an old and a new value... the rest will take care of itself. This
    * method isn't set to return anything, no value is necessary.
    *
    * @param $log_type
    * @param null $log_old
    * @param null $log_new
    * @return void
    */
    public function log($log_type, $log_new = null, $log_old = null): void
    {
        TaskLogRepository::logTask([
            'project_id'             => $this->taskChecklist->task->project_id,
            'task_id'                => $this->taskChecklist->task_id,
            'user_id'                => Auth::id(),
            'task_checklist_id'      => $this->task_checklist_id,
            'task_checklist_item_id' => $this->id,
            'type'                   => $log_type,
            'old'                    => $log_old,
            'new'                    => $log_new,
            'when'                   => Carbon::now()
        ]);
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific.
    |
    */

    /**
    * Each checklist item will belong to a checklist, this item is entirely for cascading back up to the top chain
    * from where this checklist item comes from, we can do checklist_item->checklist->task->project all the way to
    * where this really belongs. This is entirely for convenience and will be rarely used.
    *
    * @return BelongsTo
    */
    public function taskChecklist(): BelongsTo
    {
        return $this->belongsTo(TaskChecklist::class, 'task_checklist_id', 'id');
    }
}
