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
    public function up(): void
    {
        if (! $this->alreadyMigrated('journal')) {
            $this->setupJournalModule();
        }

        if (! $this->alreadyMigrated('journal_achievement')) {
            $this->setupJournalAchievements();
        }

        if (! $this->alreadyMigrated('journal_dream')) {
            $this->setupJournalDream();
        }

        if (! $this->alreadyMigrated('journal_finance')) {
            $this->setupJournalFinance();
        }

        if (! $this->alreadyMigrated('journal_loan')) {
            $this->setupJournalLoan();
        }

        if (! $this->alreadyMigrated('journal_diet')) {
            $this->setupJournalDiet();
        }
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
            $table->date('when');
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
            $table->date('when');
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function setupJournalLoan(): void
    {
        Schema::create('journal_loan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('reference', 255);
            $table->float('amount');
            $table->float('interest');
            $table->datetime('when_loaned');
            $table->datetime('when_pay_back');
            $table->datetime('when_paid_back')->nullable()->default(null);
            $table->boolean('paid_back')->default(false);
        });

        Schema::table('journal_loan', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('user')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });

        Schema::create('journal_loan_payback', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('journal_loan_id');
            $table->unsignedBigInteger('user_id');
            $table->float('amount');
            $table->datetime('paid_when');
        });

        Schema::table('journal_loan_payback', function (Blueprint $table) {
            $table->foreign('journal_loan_id')
                  ->references('id')
                  ->on('journal_loan')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('user')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    public function setupJournalDiet(): void
    {
        Schema::create('journal_diet', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->date('when');
        });

        Schema::table('journal_diet', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('user')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });

        Schema::create('journal_diet_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('category', 255);
            $table->integer('calories');
            $table->string('image_url', 255)->nullable();
        });

        Schema::create('journal_diet_items', function (Blueprint $table) {
            $table->unsignedBigInteger('journal_diet_id');
            $table->unsignedBigInteger('journal_diet_item_id');
            $table->integer('quantity')->default(1);
        });

        Schema::table('journal_diet_items', function (Blueprint $table) {
            $table->foreign('journal_diet_id')
                  ->references('id')
                  ->on('journal_diet')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('journal_diet_item_id')
                  ->references('id')
                  ->on('journal_diet_item')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }
};
