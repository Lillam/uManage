<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskPriority extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'task_priority';

    /**
    * This will want to contain everything that is fillable inside the database, if the element that should be fillable
    * is not inside the array, the element will not be insertable and will always enter as a null value, or error
    * if the element is a non-nullable value
    *
    * @var array
    */
    protected $fillable = [
        'name',
        'color',
        'icon'
    ];

    /**
    * Pre define all the casts, whatever is inside this database will want to come out with the desired data cast.
    * if it is a string, then we cast it to a string, if the element wants to be a number, cast it to a number. Any
    * column in the database will want to come back their desired casting.
    *
    * @var array
    */
    protected $casts = [
        'type'       => 'int',
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
    * Getting the color of the designated task priority. this will simply just returning the type of string. and in the
    * format of #ffa500/#ffffff/#f1f1f1f1 Etc. this method will be called mainly in a style attribute.
    *
    * @return string
    */
    public function getColor(): string
    {
        return $this->color !== null ? "#{$this->color}" : '';
    }


    /**
    * This method will be grabbing a badge icon for the task priority in question, however if the icon doesn't exist
    * then we don't really want to return anything, however if we do have something then we are going to want to be
    * inserting this, and checking if we have a background color for the icon, and if we do, then apply that logic to the
    * css string that we're applying to the badge. this logic set will be better handled inside a repository class.
    *
    * @return string
    */
    public function getBadge(): string
    {
        if ($this->icon === null)
            return '';

        $color = $this->color !== null ? "background-color: {$this->getColor()}" : '';

        return "<span class='badge task_priority_badge' style='$color'><i class='{$this->icon}'></i></span>";
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
    * This method is so that we can acquire a bunch of tasks that belong to a specific task priority... this is mainly
    * for convenience and will mainly be utilised in reporting purposes.
    *
    * @return HasMany
    */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'task_priority_id', 'id');
    }
}
