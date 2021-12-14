<?php

use Illuminate\Support\Facades\Schema;
use Database\Migrations\MigratorChecks;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFailedJobsTable extends Migration
{
    use MigratorChecks;

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up(): void
    {
        if (! $this->alreadyMigrated('failed_jobs'))
            $this->setupFailedJobsModule();
    }

    /**
     * @return void
     */
    public function setupFailedJobsModule(): void
    {
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }
}
