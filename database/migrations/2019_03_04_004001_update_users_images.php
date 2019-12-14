<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('travel_images');

        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('travel_id')->nullable();
            $table->integer('decision');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE images ADD image MEDIUMBLOB');
        DB::statement('ALTER TABLE images ADD image_small MEDIUMBLOB');

        Schema::table('users_info', function (Blueprint $table) {
            $table->integer('image_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
