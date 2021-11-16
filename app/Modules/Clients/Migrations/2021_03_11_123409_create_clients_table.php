<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id('client_id')->index();
            $table->string('client_name', 255)->index();
            $table->string('client_director', 255)->index();
            $table->string('client_email', 100)->index();
            $table->boolean('client_status')->default(1)->index();
            $table->tinyInteger('client_send_date')->default(1)->index();
            $table->tinyInteger('client_send_period')->nullable()->index();
            $table->boolean('client_autosend')->default(0);
            $table->string('client_site', 255)->nullable();
            $table->unsignedBigInteger('client_inn');
            $table->string('client_bank', 100);
            $table->integer('client_bik');
            $table->unsignedBigInteger('client_bill');
            $table->integer('client_phone');
            $table->string('client_address', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
}
