<?php

namespace App\Models\Account;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'account';

    /**
    * @var array
    */
    protected $fillable = [
        'user_id',
        'account',
        'application',
        'password',
        'order'
    ];

    /**
    * @var array
    */
    protected $casts = [
        'user_id'     => 'integer',
        'account'     => 'string',
        'application' => 'string',
        'password'    => 'string',
        'order'       => 'integer'
    ];

    /**
    * @var bool
    */
    public $timestamps = false;

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Setters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting the information around the specific model
    | in question, in this case: The Account.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question, in this case: the Account.
    |
    */

    /**
    * This method will return the account password in a decrypted format so that the user in question will be able to
    * access their password to an account they have specified in the system.
    *
    * @return string
    */
    public function getDecryptedPassword(): string
    {
        return decrypt($this->password);
    }

    /**
    * The application name has potentially to be a rather lengthy name, however; not enough space on the frontend so
    * this is a method that will allow the user to get the application name in a shortened down condensed version.
    *
    * @param int $limit
    * @return string
    */
    public function getShortApplication(int $limit = 10): string
    {
        $extra = mb_strlen($this->application) > $limit ? '...' : '';

        return mb_substr($this->application, 0, $limit) . $extra;
    }

    /**
    * The account has potential to be a rather lengthy string, and again, not a lot of room on the frontend to display
    * this, so this method will allow the system to display the content on a line; limited to 25 characters by default
    *
    * @var int $limit
    * @return string
    */
    public function getShortAccount(int $limit = 25): string
    {
        $extra = mb_strlen($this->account) > $limit ? '...' : '';

        return mb_substr($this->account, 0, $limit) . $extra;
    }

    /**
    * The account's password variable will be a rather lengthy one due to it displaying in encrypted form, and because
    * it is encrypted, we aren't going to need to display the entire lot... in which this will allow the content to be
    * displayed on the frontend in a condensed view.
    *
    * @param int $limit
    * @return string
    */
    public function getShortPassword(int $limit = 25): string
    {
        $extra = mb_strlen($this->password) > $limit ? '...' : '';

        return mb_substr($this->password, 0, $limit) . $extra;
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: the Account.
    |
    */

    /**
    * Each account in the system is of course, going to be belonging to a user, this method is the defining principle
    * that the account in question belongs to a designated user; this will allow the user to display this only for the
    * user in question, we can also display for more powerful users; who the account belongs to.
    *
    * @return BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
