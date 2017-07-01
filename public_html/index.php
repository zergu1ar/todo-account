<?php

use \Slim\App;
use \Slim\Container;
use \Medoo\Medoo;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;
use \Todo\Validator\Checker;
use \Todo\Session\Manager as Session;
use \Predis\Client as Redis;
require_once '../vendor/autoload.php';

$container = new Container([
    'db' => new Medoo([
        'database_type' => 'mysql',
        'database_name' => 'todo',
        'server' => '127.0.0.1',
        'charset' => 'utf8',
        'username' => 'root',
        'password' => 'passwd'
    ]),
    'redis' => new Redis ([
        'scheme' => 'tcp',
        'host'   => '127.0.0.1',
        'port'   => 6379,
    ])
]);

$controller = new Todo\Controller($container, new Checker, new Session($container));

$app = new App($container);
$app->get('/register/', [$controller, 'register']);
$app->get('/auth/', [$controller, 'auth']);
$app->get('/test/', function (Request $request, Response $response) {
   die('test');
});
//$app->post('/auth/', [$controller, 'auth']);
$app->run();