<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Computers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('computers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('computertargetid')->unique();
            $table->string('name');
            $table->dateTime('last_inventory_report');
            // $table->dateTime('last_software_report');
            // $table->dateTime('last_updates_report');
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
        Schema::dropIfExists('computers');
    }
}



/*

id	int Автоматическое приращение	 
ComputerTargetId	varchar(255)	 
Name	varchar(256)	 
LastReportedInventoryTime	datetime NULL	 
LastReportedSoftInventoryTime	datetime NULL	 
LastReportedUpdatesInventoryTime	datetime NULL

*/
