<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('address');
            $table->timestamps();
        });

        DB::table('addresses')->insert([
            ['address' => 'address1'],
            ['address' => 'address2'],
            ['address' => 'address3'],
        ]);
    }

    public function down() {
        Schema::dropIfExists('addresses');
    }
};

