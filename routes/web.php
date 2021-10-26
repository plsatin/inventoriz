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


$router->get('/tree',  ['uses' => 'InfoController@showComputerTree']);


$router->group(['prefix' => 'api'], function () use ($router) {

    $router->get('v1/',  ['uses' => 'InfoController@apiVersion']);
   

    $router->get('v1/classes',  ['uses' => 'WmiClassController@showAllWmiClasses']);
    $router->get('v1/classes/{id}', ['uses' => 'WmiClassController@showOneClass']);
    // $router->post('v1/classes', ['uses' => 'WmiClassController@create']);
    // $router->delete('v1/classes/{id}', ['uses' => 'WmiClassController@delete']);
    // $router->put('v1/classes/{id}', ['uses' => 'WmiClassController@update']);


    $router->get('v1/classes/{id}/properties', ['uses' => 'WmiPropertyController@showAllPropertiesOfClass']);
    $router->get('v1/classes/{id}/properties/{property}', ['uses' => 'WmiPropertyController@showOnePropertyOfClass']);
    // $router->post('v1/classes/{id}/properties', ['uses' => 'WmiPropertyController@create']);
    // $router->delete('v1/classes/{id}/properties/{property}', ['uses' => 'WmiPropertyController@delete']);
    // $router->put('v1/classes/{id}/properties/{property}', ['uses' => 'WmiPropertyController@update']);

    
    $router->get('v1/computers',  ['uses' => 'ComputerController@showSomeComputers']);
    $router->get('v1/computers/{id}', ['uses' => 'ComputerController@showOneComputer']);
    $router->post('v1/computers', ['uses' => 'ComputerController@create']);
    $router->delete('v1/computers/{id}', ['uses' => 'ComputerController@delete']);
    $router->put('v1/computers/{id}', ['uses' => 'ComputerController@update']);


    // $router->get('v1/computers/{id}/properties',  ['uses' => 'ComputerPropertiesController@showAllPropertiesOfComputer']);
    $router->get('v1/computers/{id}/properties',  ['uses' => 'ComputerPropertiesController@showAllPropertiesOfComputerWithClasses']);
    $router->get('v1/computers/{id}/properties/{class}/{property}',  ['uses' => 'ComputerPropertiesController@showOnePropertyOfComputer']);
    $router->post('v1/computers/{id}/properties/{class}/{property}', ['uses' => 'ComputerPropertiesController@create']);
    $router->delete('v1/computers/{id}/properties/{class}/{property}', ['uses' => 'ComputerPropertiesController@delete']);
    $router->put('v1/computers/{id}/properties/{class}/{property}', ['uses' => 'ComputerPropertiesController@update']);

});