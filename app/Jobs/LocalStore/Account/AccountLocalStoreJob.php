<?php

namespace App\Jobs\LocalStore\Account;

use Illuminate\Bus\Queueable;
use App\Models\Account\Account;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Jobs\LocalStore\Destinationable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\Collection;

class AccountLocalStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Destinationable;

    /**
    * @var Account[]|Collection
    */
    private array|Collection $accounts;

    /**
     * @var array|Collection
     */
    private array|Collection $put;

    /**
    * Create a new job instance.
    *
    * @return void
    */
    public function __construct(?string $destination = 'accounts/accounts.json')
    {
        $this->setDestination($destination);

        $this->accounts = Account::all();
        $this->put      = [];
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle(): void
    {
        // begin iterating over all the accounts that we have set against this job, when the job is executed, we are
        // defining $this->accounts to be all the accounts in the system, so we can render this back.
        foreach ($this->accounts as $account) {
            $this->put[$account->id] = [
                'user_id'     => $account->user_id,
                'password'    => $account->password,
                'account'     => $account->account,
                'application' => $account->application,
                'order'       => $account->order
            ];
        }

        // after we have finished, putting all of these into a file, we're just going to dump the contents of the
        // account inside a text file, this will be along with their encrypted version of the password; so that when
        // this gets inserted back into the database, the encryption will be there - this is to protect myself from
        // having the files read for their passwords (if someone did make it on the pc) - you need to be on the
        // server to be able to see the password.
        Storage::disk('local')->put($this->getDestination(), json_encode($this->put));
    }
}
