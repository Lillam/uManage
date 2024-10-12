<?php

namespace App\Models\Account;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountAccess extends Model
{
    protected $table = 'account_access';

    protected $fillable = [
        'account_id',
        'user_id',
        'access_count'
    ];

    protected $casts = [
        'account_id'   => 'integer',
        'user_id'      => 'integer',
        'access_count' => 'integer'
    ];

    public $timestamps = false;

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
