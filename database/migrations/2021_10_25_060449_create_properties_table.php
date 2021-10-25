<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('class_id');
            $table->string('name');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('type', 50)->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('classes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}


/*

PropertyID	int	 
ClassID	int	 
Name	varchar(256)	 
Type	varchar(10)	 
Description	longtext

*/