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
        Schema::create('employees', function (Blueprint $table) {
            // $table->id();
            // $table->uuid();
            $table->uuid('id')->primary();

            $table->foreignUuid('company_id')->references('id')->on('companies');
            $table->foreignUuid('branch_id')->references('id')->on('company_branches');
            $table->foreignUuid('user_created_by')->references('id')->on('users');
            $table->foreignUuid('country_id')->references('id')->on('countries');
            $table->foreignUuid('city_id')->references('id')->on('cities');
            $table->foreignUuid('role_id')->references('id')->on('roles');
            $table->string('employee_code')->unique();
            $table->enum('employee_type',['permanent','probationary','contract']);
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone_no')->unique();
            $table->longText('address');
            $table->string('password');
            $table->date('dob');
            $table->date('joining_date');
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
        Schema::dropIfExists('employees');
    }
};
