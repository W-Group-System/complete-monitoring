<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQualitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qualities', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('grpo_no')->nullable();
            $table->string('dr_rr')->nullable();
            $table->string('location_bin')->nullable();
            $table->string('seaweeds')->nullable();
            $table->string('ocular_mc')->nullable();
            $table->string('haghag')->nullable();
            $table->string('agreed_mc')->nullable();
            $table->string('remarks')->nullable();
            $table->string('ice')->nullable();
            $table->string('moss')->nullable();
            $table->integer('sda')->nullable();

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
        Schema::dropIfExists('qualities');
    }
}
