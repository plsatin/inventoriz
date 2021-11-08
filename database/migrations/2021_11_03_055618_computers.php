<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Computers extends Migration
{
    
/** Добавляем время начала и конца инвентаризации. Добавляем счетчик инвентаризаций */


    public function up()
    {
        Schema::table('computers', function (Blueprint $table) {
            $table->dateTime('last_inventory_start')->nullable();
            $table->dateTime('last_inventory_end')->nullable();
            $table->unsignedInteger('last_inventory_index')->nullable()->index();

            $table->dropColumn('last_inventory_report');
        });
    }

    public function down()
    {
        Schema::table('computers', function (Blueprint $table) {
            $table->dateTime('last_inventory_report')->nullable();

            $table->dropColumn('last_inventory_start');
            $table->dropColumn('last_inventory_end');
            $table->dropColumn('last_inventory_index');
        });
    }
}