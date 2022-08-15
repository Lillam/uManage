<?php

namespace App\Models\Task;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Task\TaskLogRepository;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use App\Models\Traits\CompositeKey;

class TaskChecklist extends Model
{
    // use CompositeKey;

    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'task_checklist';

    // /**
    // * @var string[]
    // */
    // protected $primaryKey = ['project_id', 'task_id', 'user_id', 'id'];

    /**
    * This will contain everything that can be inserted into the table, this will be everything other than the
    * timestamp fields (created_at, updated_at). everything else that gets added will want to also be added into here
    *
    * @var string[]
    */
    protected $fillable = [
        // 'id',
        'task_id',
        'project_id',
        'user_id',
        'name',
        'is_zipped',
        'order'
    ];

    /**
    * Everything will be casted to their correct value type by default, when setting up further fields these will also
    * need to be added into this array.
    *
    * @var string[]
    */
    protected $casts = [
        // 'id'         => 'int',
        'task_id'    => 'int',
        'project_id' => 'int',
        'user_id'    => 'int',
        'name'       => 'string',
        'is_zipped'  => 'boolean',
        'order'      => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
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
    | question, in this case: the Task Checklist
    |
    */

    /**
    * This method is specifically for logging any change against a task checklist, all we need to do is pass in a log
    * type, (constant of the logging type), an old and a new value... the rest will take care of itself. This method
    * isn't set to return anything, no value is necessary.
    *
    * @param $log_type
    * @param null $log_old
    * @param null $log_new
    * @return void
    */
    public function log($log_type, $log_new = null, $log_old = null): void
    {
        TaskLogRepository::logTask([
            'project_id'        => $this->task->project_id,
            'task_id'           => $this->task_id,
            'user_id'           => Auth::id(),
            'task_checklist_id' => $this->id,
            'type'              => $log_type,
            'old'               => $log_old,
            'new'               => $log_new,
            'when'              => Carbon::now()
        ]);
    }

    /**
    * This method is specifically for utilising some temporary values that will be stored against the checklist in
    * question via the controller that is returning these. This will return the progress of a checklist group in terms
    * of a number/number. I.E 1/10 checklist items have been checked inside this checklist group.
    *
    * @return string
    */
    public function getTaskChecklistItemProgress(): string
    {
        if (empty($this->total_checklist_items) && empty($this->total_completed_checklist_items))
            return '0/0';

        return "{$this->total_checklist_items}/{$this->total_completed_checklist_items}";
    }

    /**
    * This method specifically is for utilising some temporary values that will be stored against the checklist in
    * in question via the controller that is returning these. this will return the percentage of items that are checked
    * inside a checklist group. Each checklist group will have their own designated progress bar below and we need
    * a percentage marker to be able to nicely show how far the progress is.
    *
    * @return string
    */
    public function getTaskChecklistItemPercentProgress(): string
    {
        if (
            (empty($this->total_checklist_items) && empty($this->total_completed_checklist_items)) ||
            ($this->total_checklist_items === 0 && $this->total_completed_checklist_items)
        ) return '0%';
        return round(($this->total_completed_checklist_items / $this->total_checklist_items) * 100) . '%';
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
    * Each TaskChecklist that is inserted into the database will have an Associated task_id - With this in mind, we are
    * able to pull back the Task that comes with a task checklist (on the off-chance that we are going to be wanting to
    * view all task checklists as a whole away from the task itself) - but also still highlighting where the checklist
    * belongs
    *
    * @return BelongsTo
    */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    /**
    * Each TaskChecklist has the possibility of having multiple checklist items inside of it, with this relationship
    * we are going to be able to pull all the checklist items that come with the TaskChecklist.
    *
    * @return HasMany
    */
    public function task_checklist_items(): HasMany
    {
        return $this->hasMany(TaskChecklistItem::class, 'task_checklist_id', 'id')
                    ->orderBy('order');
    }
}
