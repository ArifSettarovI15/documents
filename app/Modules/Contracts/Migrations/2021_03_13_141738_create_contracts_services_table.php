<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('contracts_services', function (Blueprint $table) {
            $table->id('cs_id');
            $table->integer('cs_contract_id')->index();
            $table->integer('cs_service_id')->index();
            $table->integer('cs_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts_services');
    }
}
