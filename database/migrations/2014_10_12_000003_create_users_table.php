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
        Schema::create('users', function (Blueprint $table) {
             $table->id();
            $table->uuid('uuid');
            // $table->uuid('uid');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->unique();
            $table->longText('address')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('salt_key');
            $table->foreignId('role_id')->constrained('roles')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('country_id')->constrained('countries')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onUpdate('cascade')
                ->onDelete('cascade');
//            $table->foreignUuid('role_id')->references('id')->on('roles');
//            $table->foreignUuid('country_id')->references('id')->on('countries');
//            $table->foreignUuid('city_id')->references('id')->on('cities');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
