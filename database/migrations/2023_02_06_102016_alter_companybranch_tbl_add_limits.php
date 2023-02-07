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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('engineer_limit')->after('password');
            $table->string('employee_limit')->after('engineer_limit');
            $table->string('secondary_email')->after('email')->unique()->nullable();
            $table->longText('logo')->nullable()->change();
            $table->text('company_key')->after('employee_limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
};
