<?php

namespace App\Factory;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class EntityManagerFactory
{
    public static function getEntityManager()
    {
        $params = [
            'dbname' => getenv('DB_DATABASE'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'host' => getenv('DB_HOST'),
            'driver' => getenv('DB_DRIVER'),
        ];
        $paths = [__DIR__ .'/app/Entity'];

        $config = ORMSetup::createAttributeMetadataConfiguration($paths, false);
        $connection = DriverManager::getConnection($params, $config);
        $entityManager = new EntityManager($connection, $config);
        return $entityManager;
    }
}