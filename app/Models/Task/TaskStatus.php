<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskStatus extends Model
{
    /**
    * @variable TYPES
    * TYPE TO DO       | (int)
    * TYPE IN PROGRESS | (int)
    * TYPE DONE        | (int)
    * TYPE DELETED     | (int)
    */
    public static $TYPE_TODO        = 1;
    public static $TYPE_IN_PROGRESS = 2;
    public static $TYPE_DONE        = 3;
    public static $TYPE_ARCHIVED    = 4;

    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'task_status';

    /**
    * This will want to contain everything that is fillable inside the database, if the element that should be fillable
    * is not inside the array, the element will not be insertable and will always enter as a null value, or error
    * if the element is a non-nullable value
    *
    * @var array
    */
    protected $fillable = [
        'type',
        'name',
        'color'
    ];

    /**
    * These are the casts that the values in the database are going to come into the system as. All elements against
    * this model that is stored within the database are going to want to have their default defined cast.
    *
    * @var array
    */
    protected $casts = [
        'type'       => 'int',
        'name'       => 'string',
        'color'      => 'string',
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
    * this method is entirely for returning a color of the status, which will be utilised in a variety of areas where
    * we might want to take the use of a badge, and apply a background color to it, rather than managing that via css
    * we can directly insert it from here, if the color is null, we want to return nothing and simply this is going to
    * be returning a hex string, this could be better handled inside a repository class.
    *
    * @return string
    */
    public function getColor(): string
    {
        return $this->color !== null ? "#$this->color" : '';
    }

    /**
    * this method is entirely for returning a badge for this particular task status, and this will be referencing
    * itself for a lot of checks, if the name is null, we don't want to try do anything here, if the color is not null
    * then the colour of the box will be applied to the badge, if the type is to do, which will be grey, then the color
    * of the text will want to be black || this will need altering in future times. this also could be better off handled
    * inside a repository class.
    *
    * @return string
    */
    public function getBadge(): string
    {
        if ($this->name === null)
            return '';

        $color = '';
        if ($this->color !== null) {
            $color = "background-color: {$this->getColor()};";

            if ($this->type === TaskStatus::$TYPE_TODO)
                $color .= ' color: #444;';
        }

        return "<span class='badge task_status_badge' style='$color'>{$this->name}</span>";
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
    * This method is for returning all the tasks that are currently against this particular status type id, this is
    * mainly used for convenience and quite possibly reporting.
    *
    * @return HasMany
    */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'task_status_id', 'id');
    }
}
