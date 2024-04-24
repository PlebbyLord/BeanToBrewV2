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
        Schema::create('temp_invs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('purchase_id');
            $table->string('item_image')->nullable();
            $table->string('item_name');
            $table->float('item_price', 8, 2);
            $table->integer('item_stock');
            $table->date('production_date');
            $table->date('expiry_date');
            $table->integer('quantity')->nullable();
            $table->string('coffee_type');
            $table->string('branch');
            $table->date('transfer_date')->nullable();
            $table->date('arrival_date')->nullable();
            $table->string('requested_by')->nullable();;
            $table->tinyInteger('transfer_status')->default(0);
            $table->longText('item_description');
            $table->timestamps();

            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_invs');
    }
};
