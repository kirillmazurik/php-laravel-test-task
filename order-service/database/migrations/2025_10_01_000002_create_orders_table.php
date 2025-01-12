<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('address_id');
            $table->decimal('total');
            $table->timestamps();

            $table
                ->foreign('address_id')->references('id')
                ->on('addresses')->onDelete('cascade');
            
            $table->softDeletes();
        });
    }

    public function down() {
        Schema::dropIfExists('orders');
    }
};

