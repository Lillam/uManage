<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
    * Seed the application's database.
    *
    * @return void
    */
    public function run()
    {
         $this->call([
             // user seeders
             UserTableSeeder::class,
             // Accounts
             AccountSeeder::class,
             // System
             SystemModuleSeeder::class,
             SystemModuleAccessSeeder::class,
             // Store
             StoreProductSeeder::class,
             // journal seeders
             JournalSeeder::class,
             // project seeders
             ProjectSeeder::class,
             //ProjectUserContributorSeeder::class,
             // task seeders
             TaskStatusSeeder::class,
             TaskPrioritySeeder::class,
             TaskIssueTypeSeeder::class,
             TaskSeeder::class,
         ]);
    }
}
