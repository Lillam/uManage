<?php

use Illuminate\Support\Facades\Schema;
use Database\Migrations\MigratorChecks;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordResetsTable extends Migration
{
    use MigratorChecks;

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up(): void
    {
        if (! $this->alreadyMigrated('password_resets'))
            $this->setupPasswordResetModule();
    }

    /**
    * @return void
    */
    public function setupPasswordResetModule(): void
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }
}
