<?php

namespace App\Models\System;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemModuleAccessUser extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'system_module_access_user';

    /**
    * @var array
    */
    protected $fillable = [
        'system_module_access_id',
        'user_id'
    ];

    /**
    * @var array
    */
    protected $casts = [
        'system_module_access_id' => 'int',
        'user_id'                 => 'int',
        'created_at'              => 'datetime',
        'updated_at'              => 'datetime'
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
    | in question.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
    * @return BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
    * @return BelongsTo
    */
    public function systemModuleAccess(): BelongsTo
    {
        return $this->belongsTo(SystemModuleAccess::class, 'system_module_access_id', 'id');
    }
}
