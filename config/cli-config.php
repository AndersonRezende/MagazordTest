<?php
use WilliamCosta\DotEnv\Environment;
use App\Factory\EntityManagerFactory;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

Environment::load(__DIR__.'/../');

// Replace with your own setup and configuration
$params = [
            'dbname' => 'magazord',
            'user' => 'root',
            'password' => 'root',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        ];
        $paths = [__DIR__ .'/../app/Entity'];

        $config = ORMSetup::createAttributeMetadataConfiguration($paths, false);
        $connection = DriverManager::getConnection($params, $config);
        $entityManager = new EntityManager($connection, $config);

// Configura o ConsoleRunner com o EntityManager
return ConsoleRunner::createHelperSet($entityManager);