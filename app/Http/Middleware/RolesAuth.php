<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Permission;
use App\Models\Role;


class RolesAuth
{
    /**
     * Handle an incoming request.
     * 
     * LINK https://medium.com/swlh/laravel-authorization-and-roles-permission-management-6d8f2043ea20
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (auth()->user()) {
            // get user role permissions
            $role = Role::findOrFail(auth()->user()->role_id);
            $permissions = $role->permissions;


            $methodName = $request->getMethod();
            $pathInfo = $request->getPathInfo();

            // return response('Unauthorized Action: ' . $pathInfo . ' : ' . $methodName , 403);

            // $route = app()->router->getRoutes()[$methodName . $pathInfo]['action']['uses'];
            $route = $request->route();
            // return response('Unauthorized Action: ' . print_r($route) , 403);
            $classNameAndAction = class_basename($route[1]['uses']);
            $className = explode('@', $classNameAndAction)[0];


            //return response('Unauthorized Action: ' . $classNameAndAction . ' : ' . $methodName , 403);
            // Unauthorized Action: UserController@showAllUsers : GET

            // check if requested action is in permissions list
            foreach ($permissions as $permission)
            {
                if ($classNameAndAction == $permission->controller . '@' . $permission->action)
                {
                    // authorized request
                    return $next($request);
                }
            }

        } else {
            return $next($request);
        }

        $responseObject = array("Response"=>"Error","data"=>array("Code"=>"0x0403","Message"=>"Unauthorized action"));
        return response()->json($responseObject, 403);

    }

}
