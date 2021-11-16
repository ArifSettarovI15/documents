<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id('company_id');
            $table->string('company_type');
            $table->string('company_name');
            $table->string('company_director');
            $table->string('company_address');
            $table->string('company_email');
            $table->string('company_phone');
            $table->string('company_inn');
            $table->string('company_bank');
            $table->string('company_bik');
            $table->string('company_bill');
            $table->string('company_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
}
