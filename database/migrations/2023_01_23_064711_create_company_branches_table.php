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
        Schema::create('company_branches', function (Blueprint $table) {
            // $table->id();
            // $table->uuid();
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->references('id')->on('companies');
            $table->foreignUuid('user_created_by')->references('id')->on('users');
            $table->foreignUuid('country_id')->references('id')->on('countries');
            $table->foreignUuid('city_id')->references('id')->on('cities');
            $table->string('branch_name');
            $table->string('email')->unique();
            $table->string('phone_no')->unique();
            $table->string('mobile_no')->nullable();
            $table->text('address');
            $table->double('latitude');
            $table->double('longitude');
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
        Schema::dropIfExists('company_branches');
    }
};
