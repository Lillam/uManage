<?php

use Illuminate\Support\Facades\Schema;
use Database\Migrations\MigratorChecks;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreModule extends Migration
{
    use MigratorChecks;

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up(): void
    {
        if (! $this->alreadyMigrated('store_product'))
            $this->setupStoreProductModule();
        if (! $this->alreadyMigrated('store_basket'))
            $this->setupStoreBasketModule();
    }

    /**
    * @return void
    */
    public function setupStoreProductModule(): void
    {
        Schema::create('store_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('alias', 255);
            $table->json('package');
            $table->decimal('price')->nullable();
            $table->timestamps();
        });
    }

    /**
    * @return void
    */
    public function setupStoreBasketModule(): void
    {
        Schema::create('store_basket', function (Blueprint $table) {
            $table->unsignedBigInteger('store_product_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::table('store_basket', function (Blueprint $table) {
            $table->primary(['store_product_id', 'user_id'], 'store_product_user_id');

            $table->foreign('store_product_id')
                ->references('id')
                ->on('store_product')
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
