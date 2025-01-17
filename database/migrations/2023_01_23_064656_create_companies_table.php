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
        Schema::create('companies', function (Blueprint $table) {
             $table->id();
            // $table->uuid();
            $table->uuid('uuid');
            $table->foreignId('user_created_by')->constrained('users')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('country_id')->constrained('countries')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onUpdate('cascade')
                ->onDelete('cascade');
//            $table->foreignUuid('user_created_by')->references('id')->on('users');
//            $table->foreignUuid('country_id')->references('id')->on('countries');
//            $table->foreignUuid('city_id')->references('id')->on('cities');
            $table->string('registration_no')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->longText('logo');
            $table->string('phone_no')->unique();
            $table->string('mobile_no')->nullable();
            $table->text('address');
            $table->double('latitude');
            $table->double('longitude');
            $table->boolean('is_approved');
            $table->string('password');
            $table->enum('status',['active','inactive']);
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
        Schema::dropIfExists('companies');
    }
};
