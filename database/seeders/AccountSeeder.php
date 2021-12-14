<?php

namespace Database\Seeders;

use App\Models\Account\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AccountSeeder extends Seeder
{
    /**
    * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
    * @return void
    */
    public function run(): void
    {
        if (! Storage::disk('local')->has('accounts/accounts.json')) return;

        $accounts = collect(json_decode(
            Storage::disk('local')
                ->get('accounts/accounts.json')
        ));

        DB::transaction(function () use ($accounts) {
            $bar = $this->command->getOutput()->createProgressBar($accounts->count());
            foreach ($accounts as $account_id => $account) {
                Account::updateOrCreate(['id' => $account_id], [
                    'id'          => $account_id,
                    'account'     => $account->account,
                    'application' => $account->application,
                    'password'    => $account->password,
                    'user_id'     => $account->user_id,
                    'order'       => $account->order
                ]);
                $bar->advance();
            } $bar->finish();
        }); DB::commit();
    }
}
