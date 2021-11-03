<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// use DB;


class Roles extends Migration
{
    
    public function up(){
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Добавляем группы по умолчанию
        DB::table('roles')->insert([
            ['name' => 'admin', 'description' => 'Роль администратора'],
            ['name' => 'user', 'description' => 'Роль пользователя']]
        );

    }


    public function down(){
        Schema::dropIfExists('roles');
    }

}