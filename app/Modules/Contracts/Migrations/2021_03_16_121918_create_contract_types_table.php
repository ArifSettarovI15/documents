<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('contract_types', function (Blueprint $table) {
            $table->id('ct_id');
            $table->string('ct_name', 150);
            $table->char('ct_litera', 5);
            $table->tinyInteger('ct_start')->default(1);
            $table->integer('ct_template')->nullable()->index();
            $table->boolean('ct_status')->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_types');
    }
}
