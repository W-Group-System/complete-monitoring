<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foms', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('quality_id')->nullable();
            $table->string('foreign_matter')->nullable();
            $table->decimal('impurities', 12, 3)->nullable();  
            $table->integer('weight')->nullable();  
            $table->decimal('percent', 12, 3)->nullable();  
            $table->decimal('parts_million', 12, 3)->nullable();  
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
        Schema::dropIfExists('foms');
    }
}
