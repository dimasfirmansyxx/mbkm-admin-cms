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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('transactions_id')->unsigned();
            $table->bigInteger('products_id')->unsigned();
            $table->integer('qty')->default(1);
            $table->decimal('price_satuan', 18, 2)->default(0);
            $table->decimal('price_total', 18, 2)->default(0);
            $table->decimal('price_purchase_satuan', 18, 2)->default(0);
            $table->decimal('price_purchase_total', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('transactions_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('products_id')->references('id')->on('products')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
};
