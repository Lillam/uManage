<?php

namespace App\Models\Task;

use Carbon\Carbon;
use App\Models\User\User;
use App\Models\Project\Project;
use App\Models\TimeLog\TimeLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Task\TaskLogRepository;
use App\Models\Traits\Getters\GetterMarkdown;
use App\Models\Traits\Setters\SetterMarkdown;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use GetterMarkdown, SetterMarkdown;

    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'task';

    /**
    * This will contain everything that can be inserted into the table, this will be everything other than the
    * timestamp fields (created_at, updated_at). everything else that gets added will want to also be added into here
    *
    * @var array
    */
    protected $fillable = [
        'project_id',
        'user_id',
        'name',
        'description',
        'task_issue_type_id',
        'assigned_user_id',
        'task_status_id',
        'task_priority_id',
        'due_date',
    ];

    /**
    * These are the casts that the values in the database are going to come into the system as. All elements against
    * this model that is stored within the database are going to want to have their default defined cast.
    *
    * @var array
    */
    protected $casts = [
        'id'                 => 'int',
        'project_id'         => 'int',
        'user_id'            => 'int',
        'task_issue_type_id' => 'int',
        'assigned_user_id'   => 'int',
        'task_status_id'     => 'int',
        'task_priority_id'   => 'int',
        'due_date'           => 'datetime',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime'
    ];

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Setters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting information around the specific model in
    | question, in this case: the Task.
    |
    */

    /**
    * This method by default will parse the html that's entered into the description; this will allow the system to
    * automatically transfer the content into parsedown, so that there's nothing needed on the controller end.
    *
    * @param mixed $value
    * @return void
    */
    public function setDescriptionAttribute(mixed $value): void
    {
        $this->attributes['description'] = $this->setParsedContent($value);
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question, in this case: the Task.
    |
    */

    /**
    * @return string
    */
    public function getShortName(): string
    {
        return strip_tags(mb_substr($this->name, 0, 16)) . '...';
    }

    /**
    * by default; the description attribute is considered a 'markdown field' which means that all html will be converted
    * into markdown before being inserted into the database, when we retrieve the data back, we are going to want to
    * parse the markdown back into html so that html web pages can render it.
    *
    * @param $value
    * @return string
    */
    public function getDescriptionAttribute($value): string
    {
        return $this->getParsedContent($value);
    }

    /**
    * This method is going to need betterfying, as this current method is going to be slow coming across the entire list
    * of tasks in the system, this will be iterating over a large amount of checklist items against the task checklists
    * as well as iterating over task checklists in general, it's going to be a hard query in order to gather the
    * necessary data to return for this task in question.
    *
    * @return string
    */
    public function getTaskProgress(): string
    {
        $completed_checklist_items = 0;
        $total_checklist_items     = 0;

        foreach ($this->task_checklists as $task_checklist) {
            foreach ($task_checklist->task_checklist_items as $task_checklist_item) {
                if ($task_checklist_item->is_checked === true)
                    $completed_checklist_items += 1;
                $total_checklist_items += 1;
            }
        }

        if ($total_checklist_items === 0)
            return "0%";
        return (string) (($completed_checklist_items / $total_checklist_items) * 100) . '%';
    }

    /**
    * @return string
    */
    public function getUrl(): string
    {
        return action('Task\TaskController@_viewTaskGet', [$this->project->code, $this->id]);
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Logging
    |-------------------------------------------------------------------------------------------------------------------
    | The methods from this point will 100% be revolving around the logging of the task model, any changes to the task
    | will be going through this log method.
    |
    */

    /**
    * This method is specifically for logging any change against a task, all we need to do is pass in a log type,
    * (constant of the logging type), an old and a new value... the rest will take care of itself. This method isn't set
    * to return anything, no value is necessary.
    *
    * @param $log_type
    * @param null $log_old
    * @param null $log_new
    *
    * @return void
    */
    public function log($log_type, $log_new = null, $log_old = null): void
    {
        TaskLogRepository::logTask([
            'user_id'    => Auth::id(),
            'project_id' => $this->project_id,
            'task_id'    => $this->id,
            'old'        => $log_old,
            'new'        => $log_new,
            'type'       => $log_type,
            'when'       => Carbon::now()
        ]);
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: the Task
    |
    */

    /**
    * This method is used so that we can get the direct project that this task is assigned to, this is useful for when
    * we are going to be looking at tasks on a singular basis and need to reference where this project comes from
    * as well as being able to simply grab the task's project badge.
    *
    * @return BelongsTo
    */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
    * Each task will have an assigned user, and providing that the task has an assigned user, then we are going to be
    * pre-fetching the user in question which will be responsible for this task in question, this will be for ease of
    * access to the user that is assigned to this particular task.
    *
    * @return BelongsTo
    */
    public function task_assigned_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id', 'id');
    }

    /**
    * Each task will have a user, this is the user which has been the one who had made the task to begin with
    * this won't be changeable at all, and the reporter will always remain the same, this will be for ease of access
    * to the user in question that had made the task.
    *
    * @return BelongsTo
    */
    public function task_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

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

    /**
    * Each task will be assigned an issue type, a type of issue that we are going to be working with, whether it's a
    * new feature, or a bug... then we know what task wants working first, or gives the user the ability to prioritise
    * the tasks that they want to work on first, this will give us ease of access to the TaskIssueType model.
    *
    * @return HasOne
    */
    public function task_issue_type(): HasOne
    {
        return $this->hasOne(TaskIssueType::class, 'id', 'task_issue_type_id');
    }

    /**
    * Each task will be assigned a status, whether that is to do, in progress, deleted or done, possibly more to come
    * in future times, however, a status will always be assigned thus we are going to need direct ease of access
    * to this particular model.
    *
    * @return HasOne
    */
    public function task_status(): HasOne
    {
        return $this->hasOne(TaskStatus::class, 'id', 'task_status_id');
    }

    /**
    * Each task will have a variety of checklists against them, which allows to have sub tasks within a task that is the
    * global, with this mind when looking at tasks you can also view the checklists of the task, this will have a sub
    * table that connects to task_checklist_item which will allow the ability to check and uncheck them as a list of
    * to do's
    *
    * @return HasMany
    */
    public function task_checklists(): HasMany
    {
        return $this->hasMany(TaskChecklist::class, 'task_id', 'id')
            ->orderBy('order');
    }

    /**
    * Each task will have a set priority, and this method allows to efficiently have a priority against a task, each
    * task will have a priority set by default, which will be required when setting up a task, the default will be
    * medium, and then from there you can choose whether or not it wants to be higher or lower priority. this connects
    * to the task priority table and gives us access to the task priority model.
    *
    * @return HasOne
    */
    public function task_priority(): HasOne
    {
        return $this->hasOne(TaskPriority::class, 'id', 'task_priority_id');
    }

    /**
    * Each task can have endless comments against them... this method allows us to efficiently grab all task comments
    * against a single task so that when looking over a task we can effectively look over the task comments as well.
    *
    * @return HasMany
    */
    public function task_comments(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'task_id', 'id')
            ->where('project_id', '=', $this->project_id);
    }

    /**
    * Each task can have endless task logs against them... this method allows us to efficiently grab all the task logs
    * against a single task, so that when we are looking over a task, we can effectively look over the task logs as
    * well.
    *
    * @return HasMany
    */
    public function task_logs(): HasMany
    {
        return $this->hasMany(TaskLog::class, 'task_id', 'id');
    }

    /**
    * It is possible to log time against tasks, and with this in mind, when a user is logging multiple times against a
    * task, or even multiple users are logging time against this task, then we are going to want to collect all time_log
    * entries that were created for this task and collate them together to get a total amount of time worked on this.
    * this will be joined on task_id...
    *
    * @return HasMany
    */
    public function task_time_logs(): HasMany
    {
        return $this->hasMany(TimeLog::class, 'task_id', 'id');
    }
}
