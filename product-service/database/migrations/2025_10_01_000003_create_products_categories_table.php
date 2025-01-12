<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('products_categories', function (Blueprint $table) {
            $table->id('id');
            $table
                ->unsignedBiginteger('product_id')
                ->unsigned();
            $table
                ->unsignedBiginteger('category_id')
                ->unsigned();
            $table
                ->foreign('product_id')->references('id')
                ->on('products')->onDelete('cascade');
            $table->foreign('category_id')->references('id')
                ->on('categories')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('products_categories');
    }
};

