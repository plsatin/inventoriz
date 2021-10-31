<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use DB;


class Roles extends Migration
{
    
    public function up(){
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('roles');
    }


    // Добавляем группу администраторов
    DB::table('roles')->insert(
        array(
            'name' => 'admin',
        );
    );
    // Добавляем группу пользователей
    DB::table('roles')->insert(
        array(
            'name' => 'user',
        );
    );

}