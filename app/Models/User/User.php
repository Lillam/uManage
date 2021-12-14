<?php

namespace App\Models\User;

use App\Models\Task\Task;
use App\Models\Account\Account;
use App\Models\Project\Project;
use App\Models\Store\StoreBasket;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use App\Models\System\SystemModuleAccess;
use App\Models\System\SystemModuleAccessUser;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use Notifiable;

    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'user';

    /**
    * The attributes that are assignable.
    *
    * @var array
    */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var string[]
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
    * The attributes that should be cast to native types.
    *
    * @var string[]
    */
    protected $casts = [
        'first_name' => 'string',
        'last_name'  => 'string',
        'email'      => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question, in this case: the The User
    |
    */

    /**
    * this method will be for returning the users full name, rather than writing out
    * $user->first_name . ' ' . $user->last_name, we can simply write $user->getFullName(); which will pretty much execute
    * the same logic set, but with less amount of work in the direct code. this will of course be checking whether or
    * not the user has a last name to user, otherwise this will be just spitting back the users first name.
    *
    * @return string
    */
    public function getFullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
    * this method will be returning the initials of the users name, Liam Taylor will be brought back as LT. this method
    * will be checking if the user has a last name, otherwise it will simply just use the first initial. otherwise it
    * will be spitting back both initials for the user in question
    *
    * @return string
    */
    public function getInitials(): string
    {
        $first_initial = $this->first_name !== null
            ? $this->first_name[0]
            : '';

        $last_initial = $this->last_name !== null
            ? $this->last_name[0]
            : '';

        return "{$first_initial}{$last_initial}";
    }

    /**
    * @return string
    */
    public function getProfileImage(): string
    {
        return Storage::disk('public')->exists("avatars/{$this->id}.jpg")
            ? Storage::disk('public')->url("avatars/{$this->id}.jpg")
            : Storage::disk('public')->url('avatars/placeholder.jpg');
    }

    /**
    * @return string
    */
    public function getUrl(): string
    {
        return action('User\UserController@_viewUserGet', $this->id);
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: the User
    |
    */

    /**
    * this method will be grabbing all of the projects that this user has against their name. this will be used in a
    * variety of locations, and this relationship model will be useful for grabbing a variety of user information
    * around the system
    *
    * @return HasMany
    */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'user_id', 'id');
    }

    /**
    * this method will be grabbing all of the tasks that the user has reported in their name, this will be used in a
    * variety of locations, and this relationship model will be useful for grabbing everything that this user has
    * reported to the system from a task perspective.
    *
    * @return HasMany
    */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'user_id', 'id');
    }

    /**
    * This method will be grabbing one entry from the user_detail table for this particular user. This table will
    * be for specific information regarding this user. Address, Job Title, phone number, postcode etc, this method
    * should in theory only ever be called on the /user page.
    *
    * @return HasOne
    */
    public function user_detail(): HasOne
    {
        return $this->hasOne(UserDetail::class, 'user_id', 'id');
    }

    /**
    * This method will be grabbing one entry from the user_setting table for this particular user, This table will be
    * for intricate settings such as colour schemes; layout schemes, if the user is wanting a new layout or the
    * old layout etc etc. This user_setting relationship will return everything that the user has set for their account.
    *
    * @return HasOne
    */
    public function user_setting(): HasOne
    {
        return $this->hasOne(UserSetting::class, 'user_id', 'id');
    }

    /**
    * Relatiosnhip which ties a user to accounts, the accounts module which will return a collection of elements from
    * the database that is tied to the user in question...
    *
    * @return HasMany
    */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'user_id', 'id');
    }

    /**
    * Relationship which ties a user to all modules in the system, this will check what modules the user in question
    * has access to... this will bring back all of them, regardless of it's status... this will just print out the
    * modules that belong to this user.
    *
    * @return HasManyThrough
    */
    public function system_module_access(): HasManyThrough
    {
        return $this->hasManyThrough(
            SystemModuleAccess::class,
            SystemModuleAccessUser::class,
            'user_id',
            'id',
            'id',
            'system_module_access_id'
        )->where('system_module_access_user.is_enabled', '=', 1);
    }

    /**
    * Relationship which ties a user to all the products inside the basket... this will allow the system to be able to
    * swiftly bring back all of the products that are against a user (the authenticated user that is signed in).
    *
    * @return HasMany
    */
    public function user_basket_products(): HasMany
    {
        return $this->hasMany(StoreBasket::class, 'user_id', 'id');
    }
}
