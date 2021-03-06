<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectSetting extends Model
{
    /**
    * @var string[]
    */
    public static $tasks_in = [
        1 => 'tasks_in_todo',
        2 => 'tasks_in_progress',
        3 => 'tasks_in_completed',
        4 => 'tasks_in_archived'
    ];

    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'project_setting';

    /**
    * @var string
    */
    protected $primaryKey = 'project_id';

    /**
    * @var array
    */
    protected $fillable = [
        'project_id',
        'view_id',
        'tasks_total',
        'tasks_in_todo',
        'tasks_in_progress',
        'tasks_in_completed',
        'tasks_in_archived'
    ];

    /**
    * @var array
    */
    protected $casts = [
        'project_id'         => 'integer',
        'view_id'            => 'integer',
        'tasks_total'        => 'integer',
        'tasks_in_todo'      => 'integer',
        'tasks_in_progress'  => 'integer',
        'tasks_in_completed' => 'integer',
        'tasks_in_archived'  => 'integer',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime'
    ];

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question, in this case: the Project Setting.
    |
    */

    public function getProjectView(): string
    {
        return '';
    }

    public function getProjectViews(): array
    {
        return [];
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: the Project Setting.
    |
    */

    /**
    * Each ProjectSetting that gets inserted into the database will be associated to a Project - This will allow us to
    * view all the projectsettings in the system as a global and then associate where the ProjectSetting is belonging
    * this won't ever really be needed, however is in place for efficiency sake on the offchance that it might be
    * necessary
    *
    * @return BelongsTo
    */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
