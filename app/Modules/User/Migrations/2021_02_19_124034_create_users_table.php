<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->engine = 'MyISAM';
                $table->id('id');
                $table->char('login', 50)->unique();
                $table->string('password', 100);
                $table->string('salt', 10);
                $table->string('email', 100)->nullable();
                $table->string('remember_token', 255)->nullable();
                $table->boolean('active')->default(0);
                $table->integer('regtime', false, true)->default(time());
                $table->boolean('auth')->nullable();
                $table->tinyInteger('role_id')->default(2);
                $table->timestamp('email_verified_at')->nullable();

            }

        );


        Schema::create(
            'users_hashes',
            function (Blueprint $table) {
                $table->engine = 'MyISAM';
                $table->bigIncrements('id');
                $table->string('hash', 32)->default('');
                $table->integer('user_id');
                $table->integer('time', false, true)->default(time());
                $table->string('type', 50)->default('');
                $table->string('data', 255);
            }
        );

        Schema::create(
            'users_profile',
            function (Blueprint $table) {
                $table->engine = 'MyISAM';
                $table->bigIncrements('profile_user_id');
                $table->string('profile_name', 75)->default('');
                $table->string('profile_lastname', 100)->default('');
                $table->string('profile_phone', 30)->default('');
                $table->string('profile_payments', 100)->default('');
                $table->integer('profile_bonus')->default(0);
                $table->boolean('profile_subscribed')->default(false);
                $table->boolean('profile_notify')->default(false);
                $table->string('profile_company', 250)->default('');
                $table->string('profile_city', 100)->default('');
                $table->string('profile_inn', 50)->default('');
                $table->string('profile_site', 50)->default('');
                $table->string('profile_bank', 50)->default('');
                $table->string('profile_bik', 50)->default('');
                $table->string('profile_bank_account', 50)->default('');
                $table->string('profile_corr_account', 50)->default('');
                $table->string('profile_kpp', 50)->default('');
                $table->string('profile_ogrn', 50)->default('');
                $table->integer('profile_balance',)->default(0);
                $table->integer('profile_timeout',)->default(0);
                $table->integer('profile_credit',)->default(0);
                $table->integer('profile_discount',)->default(0);
                $table->string('profile_discounts', 2000)->default('');
                $table->integer('profile_price_id',)->default(1);
                $table->integer('profile_delivery',)->default(0);
                $table->integer('profile_icon',)->default(0);
            }
        );

//        Schema::create(
//            'users_session',
//            function (Blueprint $table) {
//                $table->engine = 'MyISAM';
//                $table->string('sessionhash', 64)->unique()->primary();
//                $table->string('user_hash', 32);
//                $table->integer('user_id');
//                $table->integer('last_activity');
//                $table->string('host', 15);
//            }
//        );

        Schema::create(
            'users_strikes',
            function (Blueprint $table) {
                $table->engine = 'MyISAM';
                $table->id()->unsigned();
                $table->string('login', 100)->nullable();
                $table->string('ip', 100);
                $table->integer('last_activity');
                $table->integer('time')->default(time());
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('users_hashes');
        Schema::dropIfExists('users_strikes');
        Schema::dropIfExists('users_profile');
    }
}
