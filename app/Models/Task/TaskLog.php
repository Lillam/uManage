<?php

namespace App\Models\Task;

use App\Models\User\User;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use App\Models\Traits\CompositeKey;

class TaskLog extends Model
{
    // use CompositeKey;

    // Task oriented constants.
    const TASK_MAKE = 1;
    const TASK_NAME = 2;
    const TASK_DESCRIPTION = 3;
    const TASK_DUE_DATE = 4;
    const TASK_ASSIGNED_USER = 5;
    const TASK_ISSUE_TYPE = 6;
    const TASK_PRIORITY = 7;
    const TASK_STATUS = 8;
    const TASK_COMMENT = 9;

    // Task checklist oriented constants.
    const TASK_CHECKLIST_MAKE = 10;
    const TASK_CHECKLIST_NAME = 11;

    // task checklist item oriented constants.
    const TASK_CHECKLIST_ITEM_MAKE = 12;
    const TASK_CHECKLIST_ITEM_NAME = 13;
    const TASK_CHECKLIST_ITEM_CHECKED = 14;

    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'task_log';

    // /**
    // * @var string[]
    // */
    // protected $primaryKey = ['id', 'project_id', 'task_id', 'user_id'];

    /**
    * @var array
    */
    protected $fillable = [
        //'id',
        'user_id',
        'task_id',
        'project_id',
        'task_checklist_id',
        'task_checklist_item_id',
        'task_comment_id',
        'type',
        'old',
        'new',
        'when'
    ];

    /**
    * @var array
    */
    protected $casts = [
        //'id'                     => 'int',
        'user_id'                => 'int',
        'project_id'             => 'int',
        'task_id'                => 'int',
        'task_checklist_id'      => 'int',
        'task_checklist_item_id' => 'int',
        'task_comment_id'        => 'int',
        'type'                   => 'int',
        'old'                    => 'string',
        'new'                    => 'string',
        'when'                   => 'datetime'
    ];

    // /**
    // * @var bool
    // */
    //public $incrementing = false;

    /**
    * @var bool
    */
    public $timestamps = false;

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question.
    |
    */

    /**
    * This is paired with the constants against this class; this will be returning the update type in plain english...
    *
    * @param null|int $type
    * @return string[]|string
    */
    public function getTypes($type = null)
    {
        $types = [
            self::TASK_MAKE                   => 'TASK_MAKE',
            self::TASK_NAME                   => 'TASK_NAME',
            self::TASK_DESCRIPTION            => 'TASK_DESCRIPTION',
            self::TASK_DUE_DATE               => 'TASK_DUE_DATE',
            self::TASK_ASSIGNED_USER          => 'TASK_ASSIGNED_USER',
            self::TASK_ISSUE_TYPE             => 'TASK_ISSUE_TYPE',
            self::TASK_PRIORITY               => 'TASK_PRIORITY',
            self::TASK_STATUS                 => 'TASK_STATUS',
            self::TASK_COMMENT                => 'TASK_COMMENT',
            self::TASK_CHECKLIST_MAKE         => 'TASK_CHECKLIST_MAKE',
            self::TASK_CHECKLIST_NAME         => 'TASK_CHECKLIST_NAME',
            self::TASK_CHECKLIST_ITEM_MAKE    => 'TASK_CHECKLIST_ITEM_MAKE',
            self::TASK_CHECKLIST_ITEM_NAME    => 'TASK_CHECKLIST_ITEM_NAME',
            self::TASK_CHECKLIST_ITEM_CHECKED => 'TASK_CHECKLIST_ITEM_CHECKED',
        ];

        return $type !== null ? $types[$type] : $types;
    }

    /**
    * @return string
    */
    public function getType(): string
    {
        return $this->getTypes($this->type);
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Checkers
    |-------------------------------------------------------------------------------------------------------------------
    | All  of these methods from below are strictly going to be for checking whether ot not something is a specific type
    | of update, item etc etc.
    |
    */

    /**
    * Method for finding out whether this entry has the update type that fits into the task updates or not. and if
    * it does, then we are working with tasks... this method is simply going to return either true or false depending on
    * type
    *
    * @return bool
    */
    public function isTaskEdit(): bool
    {
        if (in_array($this->type, [
            self::TASK_MAKE,
            self::TASK_NAME,
            self::TASK_DESCRIPTION,
            self::TASK_DUE_DATE,
            self::TASK_COMMENT,
            self::TASK_ASSIGNED_USER,
            self::TASK_PRIORITY,
            self::TASK_ISSUE_TYPE,
            self::TASK_STATUS
        ])) return true;
        return false;
    }

    /**
    * Method for finding out whether this entry has the update type that fits into the task checklist updates or not
    * and if it does, then we are working with task checklists... this method is simply going to return either
    * true or false depending on type.
    *
    * @return bool
    */
    public function isTaskChecklistEdit(): bool
    {
        if (in_array($this->type, [
            self::TASK_CHECKLIST_MAKE,
            self::TASK_CHECKLIST_NAME
        ])) return true;
        return false;
    }

    /**
    * Method for finding out whether this entry has the update type that fits into the task checklist item updates
    * or not and if it does, then we are working with task checklist items... this method is simply going to return
    * either true or false depending on type.
    *
    * @return bool
    */
    public function isTaskChecklistItemEdit(): bool
    {
        if (in_array($this->type, [
            self::TASK_CHECKLIST_ITEM_MAKE,
            self::TASK_CHECKLIST_ITEM_NAME,
            self::TASK_CHECKLIST_ITEM_CHECKED
        ])) return true;
        return false;
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
    * Each task log will belong to a user; and when we bring back the logs we are going to need to know what user it was
    * that happened to make a change to the tasks in any way, so that we are able to report on it.
    *
    * @return BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
    * Each task log will belong to a project, and when we bring back to logs we are going to need to know or at least
    * could potentially benefit from knowing which project that the logs belong to, this will allow us to acquire all
    * of a projects specific updates, and feed them to the project.
    *
    * @return BelongsTo
    */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
    * Each task log will need to belong to a task in some way, no matter the changes we are making anywhere around a
    * task it is a necessity that a task id is inserted into the database, in which, we can find out where directly
    * a change might have been made for the user... to report on this later.
    *
    * @return BelongsTo
    */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    /**
    * Task logs can potentially be from a task checklist, a task id will also be assigned with this, however if the
    * task checklist id is present then we are going to be assuming that a change was made to the task checklist...
    * and we will report on this in kind depending on the situational data that we're dealing with.
    *
    * @return BelongsTo
    */
    public function taskChecklist(): BelongsTo
    {
        return $this->belongsTo(TaskChecklist::class, 'task_checklist_id', 'id');
    }

    /**
    * Task logs can potentially be from a task checklist item, a task id and a task checklist id will be assigned to
    * this also, if a task checklist id is present, then we know which checklist was modified when playing with the
    * checklist ids. likewise, we also know where the checklist resides when utilising the task id.
    *
    * @return BelongsTo
    */
    public function taskChecklistItem(): BelongsTo
    {
        return $this->belongsTo(TaskChecklistItem::class, 'task_checklist_item_id', 'id');
    }

    /**
    * Task logs can potentially be a task comment, a task id, task comment id will be assigned to this also, if a task
    * comment id is present, then we know which task comment this will be, all this will do is just add to the list of
    * activity that is against a project against a task... and pull the comment that was made.
    *
    * @return BelongsTo
    */
    public function taskComment(): BelongsTo
    {
        return $this->belongsTo(TaskComment::class, 'task_comment_id', 'id');
    }
}
