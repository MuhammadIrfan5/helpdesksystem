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
            $table->uuid();
            $table->foreignId('user_created_by')->constrained('users');
            $table->foreignId('country_id')->constrained('countries');
            $table->foreignId('city_id')->constrained('cities');
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
