<?php

namespace App\Models\Project;

use App\Models\Task\Task;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Getters\GetterMarkdown;
use App\Models\Traits\Setters\SetterMarkdown;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use GetterMarkdown, SetterMarkdown;

    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'project';

    /**
    * @var string[]
    */
    protected $primaryKey = 'id';

    /**
    * @var bool
    */
    public $incrementing = false;

    /**
    * @var array
    */
    protected $fillable = [
        'user_id',
        'name',
        'code',
        'description',
        'color',
        'icon'
    ];

    /**
    * @var array
    */
    protected $casts = [
        'user_id'         => 'int',
        'name'            => 'string',
        'code'            => 'string',
        'description'     => 'string',
        'color'           => 'string',
        'icon'            => 'string',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime'
    ];

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Setters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting information around the specific model in
    | question, in this case: the Project.
    |
    */

    /**
    * This method is going to automatically setting the description attribute and mutating it into something that's
    * getting parsed into markdown by default.
    *
    * @param $value
    * @return void
    */
    public function setDescriptionAttribute($value): void
    {
        $this->attributes['description'] = $this->setParsedContent($value);
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question, in this case: the Project.
    |
    */

    /**
    * This method is simply going to return a hash colour that we can use on the frontend for colouring a particular
    * item... if the colour is null, then it'll just return a blank string.
    *
    * @return string
    */
    public function getColor(): string
    {
        return $this->color !== null ? "#$this->color" : '';
    }

    /**
    * @return string
    */
    public function getTaskNumberedProgress(): string
    {
        return "{$this->project_setting->tasks_in_completed}/{$this->project_setting->tasks_total}";
    }

    /**
    * This method for returning the progress of projects in terms of the tasks that sit inside them this will be
    * mapping out a percentage against the total tasks sitting, and completed tasks sitting. and will return a
    * calculation based on these two numbers, if they aren't found then we are going to be looking to return 0%;
    *
    * @return string
    */
    public function getTaskCompletedPercentage(): string
    {
        if ($this->project_setting->tasks_total === null || $this->project_setting->tasks_total === 0)
            return "0%";

        return ((string) round((
            $this->project_setting->tasks_in_completed /
            $this->project_setting->tasks_total
        ) * 100)) . '%';
    }

    /**
    * This method is for returning progress of projects in terms of the tasks that sit inside them as (in progress)
    * this will be mapping out a percentage against the total tasks sitting, and tasks in progress sitting... returning
    * a calculation from 0% - 100%.
    *
    * @return string
    */
    public function getTaskProgressPercentage(): string
    {
        if ($this->project_setting->tasks_in_progress === null || $this->project_setting->tasks_in_progress === 0)
            return "0%";

        return ((string) round((
            $this->project_setting->tasks_in_progress /
            $this->project_setting->tasks_total
        ) * 100)) . '%';
    }

    /**
    * @return int
    */
    public function getTotalTasks(): int
    {
        return $this->project_setting->tasks_total ?? 0;
    }

    /**
    * @param $value
    * @return string
    */
    public function getDescriptionAttribute($value): string
    {
        return $this->getParsedContent($value);
    }

    /**
    * @return string
    */
    public function getUrl(): string
    {
        return route('projects.project', $this->code);
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: the Project.
    |
    */

    /**
    * This method will be for returning all the settings that are currently associated to a project. this will be
    * utilised for a variety of things, such like task statistics, how many tasks exist in this project, how many
    * completed tasks exist, what project view is the user desiring etc.
    *
    * @return HasOne
    */
    public function project_setting(): HasOne
    {
        return $this->hasOne(ProjectSetting::class, 'project_id', 'id');
    }

    /**
    * This method is convenient for bringing in all the tasks that are currently belonging to a specific project... if
    * the user in question is looking at all of their projects (with: tasks) then we can iterate over all projects
    * and then iterate over all tasks that lie within a project.
    *
    * @return HasMany
    */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id', 'id');
    }

    /**
    * Each project will be created by a user, and this is more for conveniency when looking at projects to see what
    * user had created this particular project. We are able to call Project->creator_user->first_name etc. and just get
    * information about the one who made it.
    *
    * @return HasOne
    */
    public function creator_user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
    * Each project can have multiple contributors that are essentially a linker table between project and users
    * if a user is connected to a project via being a contributor then the user will appear on the project itself
    * this is useful to quickly find out what users are currently on a project.
    *
    * @return HasMany
    */
    public function user_contributors(): HasMany
    {
        return $this->hasMany(ProjectUserContributor::class, 'project_id', 'id');
    }
}
