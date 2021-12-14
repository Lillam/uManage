<?php

use Illuminate\Support\Facades\Schema;
use Database\Migrations\MigratorChecks;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JournalModule extends Migration
{
    use MigratorChecks;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! $this->alreadyMigrated('journal'))
            $this->setupJournalModule();
        if (! $this->alreadyMigrated('journal_achievement'))
            $this->setupJournalAchievements();
        if (! $this->alreadyMigrated('journal_dream'))
            $this->setupJournalDream();
        if (! $this->alreadyMigrated('journal_finance'))
            $this->setupJournalFinance();
    }

    /**
    * @return void
    */
    public function setupJournalModule(): void
    {
        Schema::create('journal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedSmallInteger('rating')->nullable();
            $table->longText('overall')->nullable();
            $table->longText('lowest_point')->nullable();
            $table->longText('highest_point')->nullable();
            $table->date('when', 20);
            $table->timestamps();
        });

        Schema::table('journal', function (Blueprint $table) {
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
    public function setupJournalAchievements(): void
    {
        Schema::create('journal_achievement', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('journal_id');
            $table->string('name', 191);
            $table->timestamps();
        });

        Schema::table('journal_achievement', function (Blueprint $table) {
            $table->foreign('journal_id')
                ->references('id')
                ->on('journal')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
    * @return void
    */
    public function setupJournalDream(): void
    {
        Schema::create('journal_dream', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedSmallInteger('rating')->nullable();
            $table->longText('content')->nullable();
            $table->longText('meaning')->nullable();
            $table->date('when', 20);
            $table->timestamps();
        });

        Schema::table('journal_dream', function (Blueprint $table) {
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
    public function setupJournalFinance(): void
    {
        Schema::create('journal_finance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->float('spend');
            $table->text('where');
            $table->date('when', 20);
            $table->timestamps();
        });
    }
}
