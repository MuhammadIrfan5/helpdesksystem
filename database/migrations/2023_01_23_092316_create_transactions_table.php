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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('company_id')->constrained('companies')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('company_branches')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onUpdate('cascade')
                ->onDelete('cascade');
//            $table->foreignUuid('company_id')->references('id')->on('companies');
//            $table->foreignUuid('branch_id')->references('id')->on('company_branches');
//            $table->foreignUuid('payment_method_id')->references('id')->on('payment_methods');
            $table->string('amount');
            $table->string('paid_month');
            $table->date('paid_date');
            $table->string('phone_no')->nullable();
            $table->string('account_title');
            $table->string('bank_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('iban')->nullable();
            $table->longText('receipt_image')->nullable();
            $table->enum('status',['received','pending']);
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
        Schema::dropIfExists('transactions');
    }
};
