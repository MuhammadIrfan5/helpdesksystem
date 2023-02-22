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
        Schema::create('complain_type', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('type');
            $table->longText('description')->nullable();
            $table->enum('status', ['active', 'inactive']);
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
        Schema::dropIfExists('complain_types');
    }
};
