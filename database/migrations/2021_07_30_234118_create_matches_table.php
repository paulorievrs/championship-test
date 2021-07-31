<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {

            $table->uuid('id');
            $table->timestamps();
            $table->foreignId('home_team_id')->references('id')->on('teams');
            $table->foreignId('guest_team_id')->references('id')->on('teams');
            $table->integer('home_team_score')->default(0);
            $table->integer('guest_team_score')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
