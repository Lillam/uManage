<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    public $key = 0;

    /**
    * This method is specifically for seeding the system with the core necessity information to be able to begin working
    * right off of the bat. - This seeder will seed the user table with a few users that can instantly start doing
    * things.
    *
    * Run the database seeders.
    *
    * @return void
    */
    public function run()
    {
        $users = [
            $this->increment() => (object) [ 'first_name' => 'Liam',  'last_name'  => 'Taylor',  'email' => 'liam.taylor@outlook.com',   'password'   => Hash::make('lolnah111') ],
            $this->increment() => (object) [ 'first_name' => 'Jarod', 'last_name'  => 'Edwards', 'email' => 'jarod.edwards@outlook.com', 'password'   => Hash::make('lolnah111') ],
            $this->increment() => (object) [ 'first_name' => 'Jay',   'last_name'  => 'Davis',   'email' => 'jay.davis@outlook.com',     'password'   => Hash::make('lolnah111') ]
        ];

        $bar = $this->command->getOutput()->createProgressBar(count($users));

        foreach ($users as $id => $user) {
            User::updateOrCreate(['id' => $id], [
                'id'         => $id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'password'   => $user->password
            ]); $bar->advance();
        } $bar->finish();
    }

    public function increment()
    {
        return $this->key += 1;
    }
}