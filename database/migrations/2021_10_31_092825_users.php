<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Users extends Migration{
    
    public function up(){
        Schema::table('users', function (Blueprint $table) {
            $table->integer('role_id')->unsigned()->default(2);
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    public function down(){
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
            $table->dropColumn('role_id');
        });
    }
}
