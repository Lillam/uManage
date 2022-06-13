<?php

namespace Database\Migrations;

use Illuminate\Support\Facades\Schema;

trait MigratorChecks
{
    /**
    * This method is going to check whether the migration has already happened or not, this will just be checking if
    * the table inside the database exists, if it does, return true, if not, return false... and if false then you're
    * going to be able to continue with migrating.
    *
    * @param $table
    * @return bool
    */
    public function alreadyMigrated($table): bool
    {
        return Schema::hasTable($table);
    }
}