<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Softwares extends Migration {
  

    public function up(){
        Schema::create('softwares', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('computer_id');
            $table->string('name');
            $table->string('version')->nullable();
            $table->string('vendor')->nullable();
            $table->dateTime('install_date')->nullable();
            $table->string('identifying_number')->nullable();
            $table->string('uninstall_string')->nullable();
            $table->timestamps();

            $table->foreign('computer_id')->references('id')->on('computers')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    public function down() {
        Schema::dropIfExists('softwares');
    }
}