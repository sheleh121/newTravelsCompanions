<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_info', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('surname', 100)->nullable();
            $table->string('name', 100);
            $table->string('patronymic', 100);
            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('location_countries');
            $table->integer('region_id')->unsigned();
            $table->foreign('region_id')->references('id')->on('location_region');
            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('location_cities');
            $table->integer('karma')->default('0');
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
        Schema::dropIfExists('users_info');
    }
}
