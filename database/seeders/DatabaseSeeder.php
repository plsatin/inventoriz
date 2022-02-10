<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Illuminate\Database\Eloquent\Model;

use DB;
// use Eloquent;



class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // // $this->call('UsersTableSeeder');
        // // Eloquent::unguard();


        $pathWmiclasses = 'app/docs/sql/wmiclasses.sql';
        DB::unprepared(file_get_contents($pathWmiclasses));
        $this->command->info('Wmiclasses table seeded!');

        $pathWmiproperties = 'app/docs/sql/wmiproperties.sql';
        DB::unprepared(file_get_contents($pathWmiproperties));
        $this->command->info('Wmiclasses table seeded!');


        $this->call(PermissionTableSeeder::Class);


    }
}
