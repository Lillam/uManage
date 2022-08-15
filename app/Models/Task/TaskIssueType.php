<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskIssueType extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'task_issue_type';

    /**
    * Everything that is fillable (other than created_at, updated_at) wants to be inserted into here, otherwise the
    * system won't recognise the entries as a submittable value
    *
    * @var array
    */
    protected $fillable = [
        'name',
        'color',
        'icon'
    ];

    /**
    * All the entries in the database should be inserted into this casts array, so we can decide to begin with what
    * the system wants to read the values as... it's better to predefine the data types before calling them
    *
    * @var array
    */
    protected $casts = [
        'name'       => 'string',
        'color'      => 'string',
        'icon'       => 'string',
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

    /**
    * This method will be collecting the color for the task issue type in question and instantiating the colour as a hex
    * code to spit back to the user which will be directly inserting into a css string. this is so we don't have to have
    * multiple classes with pre-defined colours in css, where we can now manage this from an admin interface. this logic
    * will be better placed in a repository.
    *
    * @return string
    */
    public function getColor(): string
    {
        return $this->color !== null ? "#$this->color" : '';
    }

    /**
    * This method will be grabbing a badge icon for the task issue type in question, however if the icon doesn't exist
    * then we don't really want to return anything, however if we do have something then we are going to want to be
    * inserting this, and checking if we have a background color for the icon, and if we do, then apply that logic to the
    * css string that we're applying to the badge. this logic set will be better handled inside a repository class.
    *
    * @return string
    */
    public function getBadge(): string
    {
        if ($this->icon === null) {
            return '';
        }

        $color = '';
        if ($this->color !== null) {
            $color = $this->getColor();
        }

        return "<span class='badge task_issue_type_badge' style='background-color: $color'><i class='{$this->icon}'></i></span>";
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
    * This method is to acquire all the tasks that belong to a particular task issue type. this will be utilised more
    * so on the concept of reporting. And is entirely for convenience.
    *
    * @return HasMany
    */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'task_issue_type_id', 'id');
    }
}
