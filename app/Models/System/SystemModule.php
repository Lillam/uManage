<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class SystemModule extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'system_module';

    /**
    * @var array
    */
    protected $fillable = [
        'name',
        'status'
    ];

    /**
    * @var array
    */
    protected $casts = [
        'name'        => 'string',
        'status'      => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime'
    ];

    /**
    * @var bool
    */
    public $timestamps = true;

    /**
    * @var array
    */
    public static $statuses = [
        0 => 'disabled',
        1 => 'enabled',
        2 => 'maintenance'
    ];

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Setters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting the information around the specific model
    | in question, in this case: The System Module.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question, in this case: the System Module.
    |
    */

    /**
    * This method will just return the string definition for the status itself, acting as a enum type; which will be
    * systematically set on the frontend side of things; this will return a translation key which will point to a file
    * to grab the right name for this status
    *
    * @return string
    */
    public function getStatus(): string
    {
        return self::$statuses[$this->status];
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Scopes
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting scopes around the specific model in
    | question, in this case: the System Module.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: System Module.
    |
    */
}
