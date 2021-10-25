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
        Schema::create('wmiproperties', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('wmiclass_id');
            $table->string('name');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('property_type', 50)->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();

            $table->foreign('wmiclass_id')->references('id')->on('wmiclasses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wmiproperties');
    }
}


/*

PropertyID	int	 
ClassID	int	 
Name	varchar(256)	 
Type	varchar(10)	 
Description	longtext

*/