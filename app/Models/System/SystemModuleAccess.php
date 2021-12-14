<?php

namespace App\Models\System;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemModuleAccess extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'system_module_access';

    /**
    * @var array
    */
    protected $fillable = [
        'system_module_id',
        'controller',
        'method'
    ];

    /**
    * @var array
    */
    protected $casts = [
        'system_module_id' => 'int',
        'controller'       => 'string',
        'method'           => 'string',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime'
    ];

    /**
    * @var bool
    */
    public $timestamps = true;

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Setters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting the information around the specific model
    | in question, in this case: The System Module Access.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question, in this case: the System Module Access.
    |
    */

    /**
    * @return string
    */
    public function getControllerMethod(): string
    {
        return "{$this->controller}@{$this->method}";
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: System Module Access.
    |
    */

    /**
    * Each system module access that resides against a user... has the relation to a system module... and we can get
    * name of the system module, and all of them that are currently against a user... so we can see which packages that
    * a user already has access to.
    *
    * @return BelongsTo
    */
    public function system_module(): BelongsTo
    {
        return $this->belongsTo(SystemModule::class, 'system_module_id', 'id');
    }

    /**
    * Each system access that sits in the system, will be assigned to a user, and with this in mind, we are going
    * to be able to pull back all the accesses with the user, this relationship would predominantly be utilised for
    * reporting purposes to see who has access to what particular elements of the system.
    *
    * @return BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
