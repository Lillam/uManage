<?php

namespace App\Models\TimeLog;

use App\Models\Task\Task;
use App\Models\User\User;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Getters\GetterMarkdown;
use App\Models\Traits\Setters\SetterMarkdown;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TimeLog extends Model
{
    use GetterMarkdown, SetterMarkdown;

    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'time_log';

    /**
    * @var array
    */
    protected $fillable = [
        'task_id',
        'project_id',
        'user_id',
        'note',
        'from',
        'to',
        'time_spent'
    ];

    /**
    * @var array
    */
    protected $casts = [
        'task_id'    => 'int',
        'project_id' => 'int',
        'user_id'    => 'int',
        'note'       => 'string',
        'from'       => 'datetime',
        'to'         => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Setters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting information around the specific model in
    | question.
    |
    */

    /**
    * This method is entirely for automatically setting the note attribute to be converted into parsedown when the new
    * element has been saved.
    *
    * @param $value
    * @return void
    */
    public function setNoteAttribute($value): void
    {
        $this->attributes['note'] = $this->setParsedContent($value);
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
    public function getNoteAttribute($value): string
    {
        return $this->getParsedContent($value);
    }

    /**
    * This method is for returning an amount of content, in a shorter format, this will be limiting the content to
    * (x) characters long, currently set as (20), and will append a ... after that has been accomplished.
    *
    * @return string
    */
    public function getShortNote(): string
    {
        return strip_tags(mb_substr($this->note, 0, 35)) . '...';
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
    * Every time logging entry will be connected to a user, each time_log entry will only ever be connected to One user
    * this will be an effective method for acquiring the user in question that we are dealing with...
    *
    * @return HasOne
    */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
    * Every time logging entry will be connected to a task, there's no sense on logging time against nothing, and each
    * time_log entry that enters the database will have One task to bring back from it.
    *
    * @return HasOne
    */
    public function task(): HasOne
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }

    /**
    * Every time logging entry will be connected to a project, there's no sense on logging time against nothing, and
    * each time_log entry that enters the database will have one Project to bring back from it.
    *
    * @return HasOne
    */
    public function project(): HasOne
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }
}
