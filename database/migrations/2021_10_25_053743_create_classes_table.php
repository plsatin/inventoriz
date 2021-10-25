<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('namespace')->default('root\cimv2');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('enabled')->default(1);
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
        Schema::dropIfExists('classes');
    }
}


/*

ClassID	int	 
Name	varchar(256)	 
Namespace	varchar(256) [root\cimv2]	 
Title	varchar(256)	 
Description	longtext	 
Icon	varchar(256)	 
Enabled	int [1]	 

*/