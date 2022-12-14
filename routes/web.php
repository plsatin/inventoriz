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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

/** Отображение интерфейса пользователя */
$router->get('/',  ['uses' => 'HomeController@index']);
$router->get('/tree',  ['uses' => 'HomeController@showComputerTree']);
$router->get('/softwares',  ['uses' => 'HomeController@showSoftwaresList']);
$router->get('/computers',  ['uses' => 'HomeController@showComputersList']);


/** Информация о маршрутах */
$router->get('/routes', ['uses' => 'HomeController@getApiRoutes']);





$router->group(['prefix' => 'api'], function () use ($router) {


    /** Авторизация */
    $router->post('register', ['uses' => 'AuthController@register']);
    $router->post('login', ['uses' => 'AuthController@login']);
    $router->post('logout', ['uses' => 'AuthController@logout']);
    $router->get('profile', 'UserController@profile');
    $router->get('users/{id}', 'UserController@singleUser');
    $router->get('users', 'UserController@allUsers');


    /** Информация о версии API */
    $router->get('v1/',  ['uses' => 'HomeController@getApiVersion']);


    /** Запрос на скачивание json-файла с классами и их свойствами */
    $router->get('v1/downloads/classes-json', ['uses' => 'JsonFileController@classesJsonFileDownload']);
    $router->get('v1/downloads/properties-json', ['uses' => 'JsonFileController@propertiesJsonFileDownload']);


    /** Работа с WMI классами */
    $router->get('v1/classes',  ['uses' => 'WmiClassController@showAllWmiClasses']);
    // $router->get('v1/classes',  ['uses' => 'WmiClassController@showSomeWmiClasses']);
    $router->get('v1/classes/{id}', ['uses' => 'WmiClassController@showOneWmiClass']);
    // $router->post('v1/classes', ['uses' => 'WmiClassController@create']);
    // $router->delete('v1/classes/{id}', ['uses' => 'WmiClassController@delete']);
    // $router->put('v1/classes/{id}', ['uses' => 'WmiClassController@update']);


    /** Работа со свойствами WMI классов */
    $router->get('v1/classes/{id}/properties', ['uses' => 'WmiPropertyController@showAllPropertiesOfWmiClass']);
    $router->get('v1/classes/{id}/properties/{property}', ['uses' => 'WmiPropertyController@showOnePropertyOfWmiClass']);
    // $router->post('v1/classes/{id}/properties', ['uses' => 'WmiPropertyController@create']);
    // $router->delete('v1/classes/{id}/properties/{property}', ['uses' => 'WmiPropertyController@delete']);
    // $router->put('v1/classes/{id}/properties/{property}', ['uses' => 'WmiPropertyController@update']);


    /** Работа с компьютерами  */
    $router->get('v1/computers',  ['uses' => 'ComputerController@showSomeComputers']);
    $router->get('v1/computers/{id}', ['uses' => 'ComputerController@showOneComputer']);
    $router->post('v1/computers', ['uses' => 'ComputerController@create']);
    $router->delete('v1/computers/{id}', ['uses' => 'ComputerController@delete']);
    $router->put('v1/computers/{id}', ['uses' => 'ComputerController@update']);


    /** Работа со свойствами компьютеров */
    $router->get('v1/computers/{id}/properties',  ['uses' => 'ComputerPropertiesController@showAllPropertiesOfComputer']);
    $router->get('v1/computers/{id}/properties/{class}',  ['uses' => 'ComputerPropertiesController@showClassPropertiesOfComputer']);
    $router->get('v1/computers/{id}/properties/{class}/{property}',  ['uses' => 'ComputerPropertiesController@showOnePropertyOfComputer']);
    $router->post('v1/computers/{id}/properties/{class}/{property}', ['uses' => 'ComputerPropertiesController@create']);
    $router->delete('v1/computers/{id}/properties/{class}/{property}', ['uses' => 'ComputerPropertiesController@delete']);
    $router->delete('v1/computers/{id}/classes/{class}', ['uses' => 'ComputerPropertiesController@deleteWmiClass']);
    $router->put('v1/computers/{id}/properties/{class}/{property}', ['uses' => 'ComputerPropertiesController@update']);


    /** Для построение дерева */
    $router->get('v1/computer-name',  ['uses' => 'ComputerTreeController@getComputerName']);
    $router->get('v1/computers-list',  ['uses' => 'ComputerTreeController@showSomeComputers']);
    $router->get('v1/computers/{id}/hardware',  ['uses' => 'ComputerTreeController@showAllPropertiesOfComputerDeviceTree']);
    $router->get('v1/computers/{id}/properties',  ['uses' => 'ComputerTreeController@showAllPropertiesOfComputerTree']);
    $router->get('v1/computers/{id}/software',  ['uses' => 'ComputerTreeController@showAllPropertiesOfComputerSoftwareTree']);


    /** Выборки для отчетов и графиков */
    $router->get('v1/reports/computers/properties/{property}', ['uses' => 'ReportsController@getComputersProperty']);
    $router->get('v1/reports/computers/last_updated', ['uses' => 'ReportsController@getComputersUpdatedAt']);
    $router->get('v1/reports/computers/list', ['uses' => 'ReportsController@getComputersList']);
    $router->get('v1/reports/softwares/list', ['uses' => 'ReportsController@getSoftwaresList']);

    /** Создание временной таблицы [tmp_softwares] */
    $router->get('v1/reports/softwares/table', ['uses' => 'SoftwareController@createSoftwaresTable']);

    


});