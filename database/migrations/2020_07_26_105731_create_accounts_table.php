<?php

use Illuminate\Support\Facades\Schema;
use Database\Migrations\MigratorChecks;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    use MigratorChecks;

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        if (! $this->alreadyMigrated('account'))
            $this->setupAccountModule();
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
}
