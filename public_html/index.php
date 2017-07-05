<?php

use Slim\App;
use Medoo\Medoo;
use Slim\Container;
use Predis\Client as Redis;
use Zergular\Todo\Controller;
use Zergular\Todo\User\Manager;
use Zergular\Todo\Validator\Checker;
use Zergular\Todo\Session\Manager as Session;

require_once __DIR__ . '/../vendor/autoload.php';

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
        'host' => '127.0.0.1',
        'port' => 6379,
    ])
]);

$controller = new Controller(
    new Checker,
    new Session($container->get('redis')),
    new Manager($container->get('db'))
);

$app = new App($container);
$app->post('/register/', [$controller, 'register']);
$app->post('/auth/', [$controller, 'auth']);
$app->get('/checkAuth/', [$controller, 'checkAuth']);
$app->get('/findUser/', [$controller, 'findUser']);
$app->get('/getUserNameById/', [$controller, 'getUserNameById']);
$app->post('/logout/', [$controller, 'logout']);
$app->run();
