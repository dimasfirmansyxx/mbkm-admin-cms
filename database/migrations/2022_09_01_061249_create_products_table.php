<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200);
            $table->string('code', 50);
            $table->bigInteger('product_categories_id')->unsigned();
            $table->decimal('price', 18, 2)->default(0);
            $table->decimal('purchase_price', 18, 2)->default(0);
            $table->string('short_description', 250)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status');
            $table->tinyInteger('new_product');
            $table->tinyInteger('best_seller');
            $table->tinyInteger('featured');
            $table->timestamps();

            $table->foreign('product_categories_id')->references('id')->on('product_categories')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
