<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Permissions extends Migration{
    
    public function up(){
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('key')->nullable();
            $table->string('controller');
            $table->string('action');
            $table->string('method');
            $table->timestamps();
           });
    }

    public function down(){
        Schema::dropIfExists('permissions');
    }
}