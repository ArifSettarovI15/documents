<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id('contract_id');
            $table->char('contract_numder',12);
            $table->integer('contract_supplier')->index();
            $table->timestamp('contract_date');
            $table->tinyInteger('contract_clients')->default(0);
            $table->boolean('contract_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
}
