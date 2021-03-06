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
$router->get('/chart', 'ChartController@render');
$router->get('/chart/perDept', 'ChartController@getByJurusan');
$router->get('/chart/perYear', 'ChartController@getByYear');
$router->get('/chart/wordCloud', 'ChartController@wordCloud');
$router->get('/table', 'TableController@render');
