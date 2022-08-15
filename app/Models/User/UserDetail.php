<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetail extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'user_detail';

    /**
    * @var array
    */
    protected $fillable = [
        'user_id',
        'address_line_1',
        'address_line_2',
        'town',
        'city',
        'postcode'
    ];

    /**
    * @var array
    */
    protected $casts = [
        'user_id'        => 'int',
        'address_line_1' => 'string',
        'address_line_2' => 'string',
        'town'           => 'string',
        'city'           => 'string',
        'postcode'       => 'string'
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
    * This relationship is strictly for the ability to be able to bring the user along with
    * user details, there may be minimal cases for this however, if we are looking to bring all users back that
    * are belonging to user details... then we can grab the user along with the user details...
    * UserDetail::all()->with('user');
    * $user_detail->user->first_name etc. for convenience more than anything.
    *
    * @return BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
