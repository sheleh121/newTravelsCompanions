<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTravelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('travel_type_id')->unsigned();
            $table->foreign('travel_type_id')->references('id')->on('travel_types');
            $table->integer('travel_category_id')->unsigned();
            $table->foreign('travel_category_id')->references('id')->on('travel_categories');
            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('location_countries');
            $table->integer('region_id')->unsigned();
            $table->foreign('region_id')->references('id')->on('location_region');
            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('location_cities');
            $table->string('name', 100);
            $table->text('description');
            $table->integer('decision');
            $table->boolean('success')->nullable();
            $table->dateTime('date_begin');
            $table->dateTime('date_end');
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
        Schema::dropIfExists('travels');
    }
}

