<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('count');
            $table->decimal('sell_price');
            $table->decimal('total');
            $table->timestamps();

            $table
                ->foreign('order_id')->references('id')
                ->on('orders')->onDelete('cascade');
            
            $table->softDeletes();
        });
    }

    public function down() {
        Schema::dropIfExists('order_products');
    }
};

