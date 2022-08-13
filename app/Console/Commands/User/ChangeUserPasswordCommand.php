<?php

namespace App\Console\Commands\User;

use App\Models\User\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ChangeUserPasswordCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'user.password.change';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:password:change {user} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Change A User's Password";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command. that will change the target user's password to the new value.
     *
     * @return int
     */
    public function handle(): int
    {
        // get the field that we're attempting to update the user by, if this happens to be a number and can be
        // assigned a numerical value, then we're going to want to find the user via an ID otherwise we're going to
        // default back to looking fra user via an email.
        $identifierValue = $this->argument('user');
        $identifierField = (int) $identifierValue !== 0 ? 'id' : 'email';

        $user = User::query()
            ->where($identifierField, '=', $identifierValue)
            ->first();

        // if the user hasn't been found then we're going to want to stop the particular command here and alert the
        // caller that the user cannot be found and then also alert the caller what they were trying to get a user by.
        if (! $user) {
            $this->error("User by [$identifierField:$identifierValue] could not be found");
            return 1;
        }

        $user->update(['password' => Hash::make($password = $this->argument('password'))]);

        // dump out to the caller what the new value is; so that they will then be able to inform from then on as a
        // point of definitive. if we've made it here to alert the caller then the password has actually been changed
        // to the new value.
        $this->info("{$user->getFullName()}<$user->email> has had their password changed to: $password");

        return 0;
    }
}
