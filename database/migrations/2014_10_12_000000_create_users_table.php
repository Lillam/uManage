<?php

use Illuminate\Support\Facades\Schema;
use Database\Migrations\MigratorChecks;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    use MigratorChecks;

    /**
    * Run the migrations.
    *
    * This method is called and once calling this it will iterate over all the methods that are stated within this,
    * from top to bottom... when creating more additions please take into consideration the keys that are needed and
    * required, the order will want to be in which the key constraints are needed to be set...
    *
    * @return void
    */
    public function up(): void
    {
        if (! $this->alreadyMigrated('user'))
            $this->setupUserModule();

        if (! $this->alreadyMigrated('user_detail'))
            $this->setupUserDetailsModule();

        if (! $this->alreadyMigrated('user_setting'))
            $this->setupUserSettingsModule();
    }

    /**
    * @return void
    */
    public function setupUserModule(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name', 191);
            $table->string('last_name', 191);
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
    * @return void
    */
    public function setupUserDetailsModule(): void
    {
        Schema::create('user_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('job_title', 191)->nullable();
            $table->string('address_line_1', 191)->nullable();
            $table->string('address_line_2', 191)->nullable();
            $table->string('town', 191)->nullable();
            $table->string('city', 191)->nullable();
            $table->string('postcode', 50)->nullable();
        });

        Schema::table('user_detail', function (Blueprint $table) {
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
    public function setupUserSettingsModule(): void
    {
        Schema::create('user_setting', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('theme_color', 12)->nullable();
            $table->boolean('sidebar_closed')->default(false);
        });

        Schema::table('user_setting', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
