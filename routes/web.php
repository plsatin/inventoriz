<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('v1/classes',  ['uses' => 'WmiclassController@showAllWmiClasses']);
    $router->get('v1/classes/{id}', ['uses' => 'WmiclassController@showOneClass']);
    // $router->post('v1/wmiclasses', ['uses' => 'WmiclassController@create']);
    // $router->delete('v1/wmiclasses/{id}', ['uses' => 'WmiclassController@delete']);
    // $router->put('v1/wmiclasses/{id}', ['uses' => 'WmiclassController@update']);

    $router->get('v1/classes/{id}/properties', ['uses' => 'WmipropertyController@showAllPropertiesOfClass']);




});