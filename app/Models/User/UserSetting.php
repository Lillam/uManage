<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'user_setting';

    /**
    * @var array
    */
    protected $fillable = [
        'user_id',
        'theme_color',
        'sidebar_collapsed'
    ];

    /**
    * @var array
    */
    protected $casts = [
        'user_id'           => 'int',
        'theme_color'       => 'string',
        'sidebar_collapsed' => 'boolean',
    ];

    /**
    * @var bool
    */
    public $timestamps = false;

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question, in this case: the User Setting
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: the User Setting
    |
    */

    /**
    * Each setting in the database will be belonging to a user, if we are looking to collect all setting users then it
    * quite could be possible that we are also after the user that the settings belong to, this is a 1 to 1 relationship
    * and should only ever belong to one user and one user should only ever have one setting set.
    *
    * @return BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
