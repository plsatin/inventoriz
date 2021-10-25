<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ComputerProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('computer_properties', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('computer_id');
            $table->unsignedInteger('wmiclass_id');
            $table->unsignedInteger('wmiproperty_id');
            $table->string('value');
            $table->integer('instance_id')->default(1);
            $table->timestamps();

            $table->foreign('computer_id')->references('id')->on('computers')->onDelete('cascade');
            $table->foreign('wmiclass_id')->references('id')->on('wmiclasses')->onDelete('cascade');
            $table->foreign('wmiproperty_id')->references('id')->on('wmiproperties')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('computer_properties');
    }
}



/*

id	int Автоматическое приращение	 
ComputerTargetId	varchar(255)	 
ClassID	int	 
PropertyID	int	 
Value	varchar(256)	 
InstanceId	int [1]

*/
