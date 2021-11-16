<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id('plan_id');
            $table->integer('plan_contract')->index();
            $table->tinyInteger('plan_day')->default(3)->index();
            $table->integer('plan_document')->nullable()->index();
            $table->boolean('plan_status')->default(0)->index();
            $table->boolean('plan_success_this_month')->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
}
