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
    * This method is for setting up the task system in general, all things related to the tasks will be set up within
    * this particular method set. up will be running through all the set-up processes that are set within this class...
    * the process in which these are handled will need to be in order of which keys are needing to be set, a key cannot
    * be set on a row that isn't there, so be careful when adding to this ordering and take keys into consideration
    *
    * @return void
    */
    public function up()
    {
        if (! $this->alreadyMigrated('task_priority'))
            $this->setupTaskPriorityModule();

        if (! $this->alreadyMigrated('task_status'))
            $this->setupTaskStatusModule();

        if (! $this->alreadyMigrated('task_issue_type'))
            $this->setupTaskIssueTypeModule();

        if (! $this->alreadyMigrated('task'))
            $this->setupTaskModule();

        if (! $this->alreadyMigrated('task_file'))
            $this->setUpTaskFileModule();

        if (! $this->alreadyMigrated('task_comment'))
            $this->setupTaskCommentModule();

        if (! $this->alreadyMigrated('task_watcher_user'))
            $this->setupTaskWatcherUserModule();

        if (! $this->alreadyMigrated('task_checklist'))
            $this->setupTaskChecklistModule();

        if (! $this->alreadyMigrated('task_checklist_item'))
            $this->setUpTaskChecklistItemModule();

        if (! $this->alreadyMigrated('task_log'))
            $this->setupTaskLogModule();
    }

    /**
    * @return void
    */
    public function setupTaskModule(): void
    {
        Schema::create('task', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');

            $table->unsignedBigInteger('assigned_user_id')->nullable();
            $table->unsignedBigInteger('task_issue_type_id')->nullable();
            $table->unsignedBigInteger('task_status_id')->nullable();
            $table->unsignedBigInteger('task_priority_id')->nullable();
            $table->string('name', 191);
            $table->longText('description')->nullable();
            $table->dateTime('due_date')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::table('task', function (Blueprint $table) {
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

            $table->foreign('assigned_user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('task_issue_type_id')
                ->references('id')
                ->on('task_issue_type')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('task_status_id')
                ->references('id')
                ->on('task_status')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('task_priority_id')
                ->references('id')
                ->on('task_priority')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
    * @return void
    */
    public function setupTaskStatusModule(): void
    {
        Schema::create('task_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('type');
            $table->string('name', 191);
            $table->string('color',  10)->nullable();
            $table->timestamps();
        });
    }

    /**
    * @return void
    */
    public function setupTaskPriorityModule(): void
    {
        Schema::create('task_priority', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191);
            $table->string('color', 10)->nullable();
            $table->string('icon', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
    * @return void
    */
    public function setupTaskIssueTypeModule(): void
    {
        Schema::create('task_issue_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191);
            $table->string('color', 10)->nullable();
            $table->string('icon', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
    * @return void
    */
    public function setupTaskCommentModule(): void
    {
        Schema::create('task_comment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');

            $table->longText('content');
            $table->timestamps();
        });

        Schema::table('task_comment', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('task_comment')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('project_id')
                ->references('id')
                ->on('project')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('task_id')
                ->references('id')
                ->on('task')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
    public function setupTaskWatcherUserModule(): void
    {
        Schema::create('task_watcher_user', function (Blueprint $table) {
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');

            $table->unsignedBigInteger('watcher_user_id');
            $table->timestamps();
        });

        Schema::table('task_watcher_user', function (Blueprint $table) {
            $table->primary(['task_id', 'user_id'], 'task_project_user_watcher_id');

            $table->foreign('project_id')
                ->references('id')
                ->on('project')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('task_id')
                ->references('id')
                ->on('task')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('watcher_user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
    * @return void
    */
    public function setupTaskChecklistModule(): void
    {
        Schema::create('task_checklist', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger( 'task_id');
            $table->unsignedBigInteger('user_id');

            $table->string('name', 255);
            $table->boolean('is_zipped')->default(0);
            $table->unsignedSmallInteger('order')->nullable();
            $table->timestamps();
        });

        Schema::table('task_checklist', function (Blueprint $table) {
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

    /**
    * @return void
    */
    public function setUpTaskChecklistItemModule(): void
    {
        Schema::create('task_checklist_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_checklist_id');
            $table->string('name', 255);
            $table->unsignedSmallInteger('order')->nullable();
            $table->boolean('is_checked')->default(0);
            $table->timestamps();
        });

        Schema::table('task_checklist_item', function (Blueprint $table) {
            $table->foreign('project_id')
                ->references('id')
                ->on('project')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('task_id')
                ->references('id')
                ->on('task')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('task_checklist_id')
                ->references('id')
                ->on('task_checklist')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
    * @return void
    */
    public function setUpTaskFileModule(): void
    {
        Schema::create('task_file', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');
            $table->text('file');
            $table->string('mimetype');
            $table->timestamps();
        });

        Schema::table('task_file', function (Blueprint $table) {
            // Foreign Keys
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

    /**
    * @return void
    */
    public function setupTaskLogModule(): void
    {
        Schema::create('task_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_checklist_id')->nullable();
            $table->unsignedBigInteger('task_checklist_item_id')->nullable();
            $table->unsignedBigInteger('task_comment_id')->nullable();
            $table->unsignedTinyInteger('type');
            $table->longText('old')->nullable();
            $table->longText('new')->nullable();
            $table->dateTime('when');
        });

        Schema::table('task_log', function (Blueprint $table) {
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

            $table->foreign('task_id')
                ->references('id')
                ->on('task')
                ->onUpdate('cascade')
                ->onDelete('cascade');

             $table->foreign('task_checklist_id')
                ->references('id')
                ->on('task_checklist')
                ->onUpdate('cascade')
                ->onDelete('cascade');

             $table->foreign('task_checklist_item_id')
                ->references('id')
                ->on('task_checklist_item')
                ->onUpdate('cascade')
                ->onDelete('cascade');

             $table->foreign('task_comment_id')
                 ->references('id')
                 ->on('task_comment')
                 ->onUpdate('cascade')
                 ->onDelete('cascade');
        });
    }
};
