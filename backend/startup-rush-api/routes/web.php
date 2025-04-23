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

// Startups
$router->post('/startups', 'StartupController@store');
$router->get('/startups', 'StartupController@index');

// Torneio 
$router->post('/torneio/iniciar', 'TorneioController@iniciar');
$router->post('/torneio/avancar', 'TorneioController@avancar');
$router->get('/torneio/status', 'TorneioController@status');
$router->post('/torneio/resetar', 'TorneioController@resetar');

// Batalhas 
$router->get('/batalhas/pendentes', 'BatalhaController@pendentes');
$router->post('/batalhas/{id}/resolver', 'BatalhaController@resolver');

// Relatórios 
$router->get('/torneio/relatorio', 'TorneioController@relatorio');
$router->get('/torneio/relatorio-detalhado', 'TorneioController@relatorioDetalhado');


