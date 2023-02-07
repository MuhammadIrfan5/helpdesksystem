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
        Schema::create('countries', function (Blueprint $table) {
             $table->id();
            $table->uuid('uuid');
            // $table->uuid();
            $table->string('phone');
            $table->string('code');
            $table->string('name');
            $table->text('symbol');
            $table->text('capital');
            $table->text('currency');
            $table->text('continent');
            $table->text('continent_code');
            $table->text('alpha_3');
            $table->enum('status', ['active', 'inactive'])->default("inactive");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
};
