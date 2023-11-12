<?php

use App\Utils\View;
use WilliamCosta\DotEnv\Environment;
use Doctrine\DBAL\DriverManager;


Environment::load(__DIR__.'/../');

// Configurações de banco de dados
$connectionParams = [
    'dbname' => getenv('DB_DATABASE'),
    'user' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'host' => getenv('DB_HOST'),
    'driver' => getenv('DB_DRIVER'),
];
$conn = DriverManager::getConnection($connectionParams);




define('URL', getenv('URL'));

View::init(['URL' => URL]);