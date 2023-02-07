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
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('company_id')->constrained('companies')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('company_branches')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('country_id')->constrained('countries')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('employee_code')->unique();
            $table->foreignId('employee_type_id')->constrained('employee_type')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('company_email')->unique();
            $table->string('personal_email')->unique()->nullable();
            $table->string('primary_phone_no')->unique();
            $table->string('emergency_phone_no')->unique()->nullable();
            $table->longText('address_line1')->nullable();
            $table->longText('address_line2')->nullable();
            $table->string('cnic_no')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('password');
            $table->text('profile_picture')->nullable();
            $table->date('dob')->nullable();
            $table->date('joining_date')->nullable();
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
        Schema::dropIfExists('employee');
    }
};
