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
            $table->id();
            $table->uuid();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('branch_id')->constrained('company_branches');
            $table->foreignId('user_created_by')->constrained('users');
            $table->foreignId('role_id')->constrained('roles');
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
