<?php

use Illuminate\Support\Facades\Schema;
use Database\Migrations\MigratorChecks;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimelogModule extends Migration
{
    use MigratorChecks;

    /**
    * Run the migrations.
    *
    * @return void
    * This method is designed for setting up the timelog module, when creating new amendments please consider keys
    * and place them in the order of which they are going to be requiring the keys in, if a new addition requires a key
    * to be set in order to add a constraint, then it is going to need to be in place first. this will iterate over all
    * setup phases that are set within the up method.
    */
    public function up()
    {
        if (! $this->alreadyMigrated('timelog'))
            $this->setUpTimelogModule();
    }

    /**
    * @return void
    */
    public function setUpTimelogModule(): void
    {
        Schema::create('timelog', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('note')->nullable();
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->unsignedBigInteger('time_spent')->nullable();
            $table->timestamps();
        });

        Schema::table('timelog', function (Blueprint $table) {
            $table->foreign('task_id')
                ->references('id')
                ->on('task')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('project_id')
                ->references('id')
                ->on('project')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
