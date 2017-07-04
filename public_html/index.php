<?php

use \Slim\App;
use \Slim\Container;
use \Medoo\Medoo;
use \Todo\Validator\Checker;
use \Todo\Session\Manager as Session;
use \Predis\Client as Redis;
require_once __DIR__ . '../vendor/autoload.php';

header('Access-Control-Allow-Origin: *');

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

$controller = new Todo\Controller($container, new Checker, new Session($container->get('redis')));

$app = new App($container);
$app->post('/register/', [$controller, 'register']);
$app->post('/auth/', [$controller, 'auth']);
$app->get('/checkAuth/', [$controller, 'checkAuth']);
$app->get('/findUser/', [$controller, 'findUser']);
$app->get('/getUserNameById/', [$controller, 'getUserNameById']);
$app->post('/logout/', [$controller, 'logout']);
$app->run();