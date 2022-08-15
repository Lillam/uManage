<?php

namespace App\Models\Task;

use Carbon\Carbon;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Task\TaskLogRepository;
use App\Models\Traits\Getters\GetterMarkdown;
use App\Models\Traits\Setters\SetterMarkdown;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskComment extends Model
{
    use GetterMarkdown, SetterMarkdown;

    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'task_comment';

    /**
    * This will want to contain everything that is fillable inside the database, if the element that should be fillable
    * is not inside the array, the element will not be insertable and will always enter as a null value, or error
    * if the element is a non-nullable value
    *
    * @var array
    */
    protected $fillable = [
        'task_id',
        'project_id',
        'user_id',
        'parent_id',
        'content'
    ];

    /**
    * These are the casts that the values in the database are going to come into the system as. All elements against
    * this model that is stored within the database are going to want to have their default defined cast.
    *
    * @var array
    */
    protected $casts = [
        'parent_id'  => 'int',
        'task_id'    => 'int',
        'project_id' => 'int',
        'user_id'    => 'int',
        'content'    => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // /**
    // * @var bool
    // */
    //  public $incrementing = false;

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Setters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting information around the specific model in
    | question.
    |
    */

    /**
    * Prior to saving the comment attribute, this is the process that the value will go through, we are going to by
    * default set the comment through a converter parsing the string to markdown...
    *
    * @param $value
    * @return void
    */
    public function setContentAttribute($value): void
    {
        $this->attributes['content'] = $this->setParsedContent($value);
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question.
    |
    */

    /**
    * @param $value
    * @return string
    */
    public function getContentAttribute($value): string
    {
        return $this->getParsedContent($value);
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Logging
    |-------------------------------------------------------------------------------------------------------------------
    | The methods from this point will 100% be revolving around the logging of the task comment model, any changes to
    | the task_comment will be going through this log method.
    |
    */

    /**
    * This method will be revolving around logging into the database, that a task comment has been made; when a task
    * comment has been made, an entry will be thrown into the task log table so that we have access to who made this
    * comment, when this comment was made, and be able to create a feed timeline of things that has happened on a
    * project... on a task and more... this will be statistic building.
    *
    * @param $log_type
    * @param $log_new
    * @param $log_old
    *
    * @return void
    */
    public function log($log_type, $log_new = null, $log_old = null): void
    {
        TaskLogRepository::logTask([
            'user_id'         => Auth::id(),
            'project_id'      => $this->task->project_id,
            'task_id'         => $this->task_id,
            'task_comment_id' => $this->id,
            'old'             => $log_old,
            'new'             => $log_new,
            'type'            => $log_type,
            'when'            => Carbon::now()
        ]);
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
    * Each TaskComment that is made in the system, will be belonging to a task, a task comment cannot be made unless
    * it is against a task... this relationship will be for the efficiency of grabbing the task and where it belongs
    * should any system be in place to email this out, we can say this comment was made on this task, click here to
    * view (as an example).
    *
    * @return BelongsTo
    */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    /**
    * Each TaskComment that is made in the system will be belonging to a user, a comment cannot be made unless it is
    * against a user... this relationship will be for the efficiency of grabbing the user; basically highlighting where
    * this comment came from and who made it, when it was made etc. (this will be highlighted on the visuals of task
    * comments)
    *
    * @return BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
    * Comments taht have been replied to, will naturally have a parent  to which they have been assigned to. with  this
    * we are able to cascade back up to acquire the parent comment where the current comment will have came
    * from
    *
    * @return BelongsTo
    */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(TaskComment::class, 'parent_id', 'id');
    }

    /**
    * This method is for acquiring all replies that are against a comment, this is for convenience as well as being
    * able to map out all the replies of a comment. this will be as recursrive as possible. $comment->replies as $reply
    * $reply->replies etc. and keep it  going until there are no more commments...
    *
    * @return HasMany
    */
    public function replies(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'id', 'parent_id');
    }
}
