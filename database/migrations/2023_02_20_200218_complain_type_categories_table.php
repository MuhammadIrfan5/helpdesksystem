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
        Schema::create('company_complain_type',function (Blueprint $table){
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('company_id')->constrained('companies')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('complain_type_id')->constrained('complain_type')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employee')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('employee_role_id')->constrained('roles')->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('complain_type_categories');
    }
};
