<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

use DB;
use Route;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission_ids = []; // an empty array of stored permission IDs
        // iterate though all routes

        global $app;

        $routeCollection = property_exists($app, 'router') ? $app->router->getRoutes() : $app->getRoutes();

        foreach ($routeCollection as $route)
        {
            // get route action
            $action0 =(isset($route['action']['uses'])) ? $route['action']['uses'] : '';
            // separating controller and method
            if (!($action0 == '')) {
                $_action = explode('@',$action0);
                $action = $_action[1];
                $_action2 = explode('\\',$_action[0]);
                $controller = $_action2[3];
                $method = $route['method'];
                
                // echo '{"action" : "' . $action . '", "controller" : "' . $controller . '", "method" : "' . $method . '"},';
    
            



                // check if this permission is already exists
                $permission_check = Permission::where(
                        ['controller'=>$controller,'action'=>$action]
                    )->first();
                if(!$permission_check){
                    $permission = new Permission;
                    $permission->action = $action;
                    $permission->controller = $controller;
                    $permission->method = $method;
                    $permission->save();
                    // add stored permission id in array
                    $permission_ids[] = $permission->id;
                }

            }
        }
        // find admin role.
        $admin_role = Role::where('name','admin')->first();
        // atache all permissions to admin role
        $admin_role->permissions()->attach($permission_ids);
    }
}
