<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('purchase_id');
            $table->string('item_name');
            $table->string('item_image');
            $table->decimal('item_price', 10, 2);
            $table->integer('quantity');
            $table->date('expiry_date');
            $table->tinyInteger('checkout_status')->default(1);
            $table->tinyInteger('delivery_status')->default(1);
            $table->tinyInteger('rate_status')->default(1);
            $table->timestamps();

            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
