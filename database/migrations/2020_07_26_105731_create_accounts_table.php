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
    */
    public function up()
    {
        if (! $this->alreadyMigrated('account')) {
            $this->setupAccountModule();
        }

        if (! $this->alreadyMigrated('account_access')) {
            $this->setupAccountAccessModule();
        }
    }

    /**
    * @return void
    */
    public function setupAccountModule(): void
    {
        Schema::create('account', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('account', 191);
            $table->string('application', 191)->nullable();
            $table->text('password');
            $table->string('two_factor_recovery_code')->default('');
            $table->smallInteger('order');
        });

        Schema::table('account', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function setupAccountAccessModule(): void
    {
        Schema::create('account_access', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('access_count');
        });

        Schema::table('account_access', function (Blueprint $table) {
            $table->foreign('account_id')
                ->references('id')
                ->on('account')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
};
