<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Permission;
use App\Models\Role;

use DB;
use Route;


class GenPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $permission_ids = []; // an empty array of stored permission IDs
        // iterate though all routes

        global $app;

        $routeCollection = property_exists($app, 'router') ? $app->router->getRoutes() : $app->getRoutes();

        echo '[';
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
                
                echo '{"action" : "' . $action . '", "controller" : "' . $controller . '", "method" : "' . $method . '"},';


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
        echo ']';

        // find admin role.
        $admin_role = Role::where('name','admin')->first();
        // atache all permissions to admin role
        $admin_role->permissions()->attach($permission_ids);

        return 0;
    }
}
