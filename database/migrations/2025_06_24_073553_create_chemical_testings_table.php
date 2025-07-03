<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChemicalTestingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chemical_testings', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('quality_id')->nullable();
            $table->string('parameter')->nullable();      
            $table->string('specification')->nullable();      
            $table->decimal('result', 6, 2)->nullable();  
            $table->string('remarks')->nullable();   
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
        Schema::dropIfExists('chemical_testings');
    }
}
