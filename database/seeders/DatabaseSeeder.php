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
    public function run(): void
    {
         $this->call([
             UserTableSeeder::class,
             AccountSeeder::class,
             SystemModuleSeeder::class,
             SystemModuleAccessSeeder::class,
             StoreProductSeeder::class,
             JournalSeeder::class,
             JournalFinanceSeeder::class,
             JournalDreamSeeder::class,
             ProjectSeeder::class,
             // ProjectUserContributorSeeder::class,
             TaskStatusSeeder::class,
             TaskPrioritySeeder::class,
             TaskIssueTypeSeeder::class,
             TaskSeeder::class,
         ]);
    }
}
