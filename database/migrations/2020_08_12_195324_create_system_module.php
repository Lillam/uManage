<?php

use Illuminate\Support\Facades\Schema;
use Database\Migrations\MigratorChecks;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemModule extends Migration
{
    use MigratorChecks;

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up(): void
    {
        if (! $this->alreadyMigrated('system_changelog'))
            $this->setupSystemChangelogModule();

        if (! $this->alreadyMigrated('system_module'))
            $this->setupSystemModuleModule();

        if (! $this->alreadyMigrated('system_module_access'))
            $this->setupSystemModuleAccessModule();

        if (! $this->alreadyMigrated('system_module_access_user'))
            $this->setupSystemModuleAccessUserModule();
    }

    /**
    * @return void
    */
    public function setupSystemChangelogModule(): void
    {
        Schema::create('system_changelog', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('content');
            $table->timestamps();
        });

        Schema::table('system_changelog', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('user')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    /**
    * @return void
    */
    public function setupSystemModuleModule(): void
    {
        Schema::create('system_module', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 350);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
    * @return void
    */
    public function setupSystemModuleAccessModule(): void
    {
        Schema::create('system_module_access', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('system_module_id');
            $table->string('controller',  350);
            $table->string('method', 350);
            $table->timestamps();
        });

        Schema::table('system_module_access', function (Blueprint $table) {
            $table->foreign('system_module_id')
                ->references('id')
                ->on('system_module')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
    * @return void
    */
    public function setupSystemModuleAccessUserModule(): void
    {
        Schema::create('system_module_access_user', function (Blueprint $table) {
            $table->unsignedBigInteger('system_module_access_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_enabled')->default(0);
            $table->timestamps();
        });

        Schema::table('system_module_access_user', function (Blueprint $table) {
            $table->primary(['system_module_access_id', 'user_id'], 'system_module_access_user_id');

            $table->foreign('system_module_access_id')
                ->references('id')
                ->on('system_module_access')
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
