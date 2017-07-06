<?php

use Slim\App;
use Medoo\Medoo;
use Slim\Container;
use Predis\Client as Redis;
use Zergular\Todo\Controller;
use Zergular\Todo\User\Manager;
use Zergular\Todo\Validator\Checker;
use Zergular\Todo\Session\Manager as Session;
use Zergular\Common\Config;

require_once __DIR__ . '/../vendor/autoload.php';
Config::setDir(__DIR__ . '/../config/');
$db = Config::get('db');
$redis = Config::get('redis');

header('Access-Control-Allow-Origin: *');

$container = new Container([
    'db' => new Medoo($db),
    'redis' => new Redis ($redis)
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
