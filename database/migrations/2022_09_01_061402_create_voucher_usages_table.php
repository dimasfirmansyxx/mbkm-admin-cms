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
        Schema::create('voucher_usages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('transactions_id')->unsigned();
            $table->bigInteger('vouchers_id')->unsigned();
            $table->decimal('discounted_value', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('transactions_id')->references('id')->on('transactions')->onDelete('restrict');
            $table->foreign('vouchers_id')->references('id')->on('vouchers')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher_usages');
    }
};
