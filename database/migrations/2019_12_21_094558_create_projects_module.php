<?php

use Illuminate\Support\Facades\Schema;
use Database\Migrations\MigratorChecks;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use MigratorChecks;

    /**
    * Run the migrations.
    *
    * @return void
    * This method is for setting up the project module, this will iterate over everything 1x1 in the order specified in
    * the method. please consider keys when making amendments and ensure that the keys that are required are in place
    * for the new elements added.
    */
    public function up(): void
    {
        if (! $this->alreadyMigrated('project'))
            $this->setupProjectModule();

        if (! $this->alreadyMigrated('project_setting'))
            $this->setupProjectSettingsModule();

        if (! $this->alreadyMigrated('project_user_contributor'))
            $this->setupProjectUserContributorModule();
    }

    /**
    * @return void
    */
    public function setupProjectModule(): void
    {
        Schema::create('project', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name', 191);
            $table->string('code', 2)
                ->comment('if left blank, will be generated from first 2 characters of title')
                ->unique();
            $table->longText('description')
                ->nullable();
            $table->string('icon', 30)
                ->nullable();
            $table->string('color', 10)
                ->nullable();
            $table->timestamps();
        });

        Schema::table('project', function (Blueprint $table) {
            // Foreign Key
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
    * @return void
    */
    public function setupProjectSettingsModule(): void
    {
        Schema::create('project_setting', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id');
            $table->unsignedSmallInteger('view_id')->comment('Enum column, values stored in code.');

            // Task Statistics...
            $table->integer('tasks_total')->default(0);
            $table->integer('tasks_in_todo')->default(0);
            $table->integer('tasks_in_progress')->default(0);
            $table->integer('tasks_in_completed')->default(0);
            $table->integer('tasks_in_archived')->default(0);

            // timestamps.
            $table->timestamps();
        });

        Schema::table('project_setting', function (Blueprint $table) {
            $table->foreign('project_id')
                ->references('id')
                ->on('project')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
    * @return void
    */
    public function setupProjectUserContributorModule(): void
    {
        Schema::create('project_user_contributor', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id');
            $table->timestamps();
        });

        Schema::table('project_user_contributor', function (Blueprint $table) {
            $table->primary(['user_id', 'project_id'], 'user_project_contributor_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('project_id')
                ->references('id')
                ->on('project')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
};
