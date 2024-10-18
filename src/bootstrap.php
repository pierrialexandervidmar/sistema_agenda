<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php";

$paths = [__DIR__ . '/Entity'];
$isDevMode = true;

$dbParams = [
    'driver'   => 'pdo_pgsql',          
    'user'     => 'root',          
    'password' => 'Senha!123',          
    'dbname'   => 'agenda', 
    'host'     => 'postgres-container',
];

// Criação da configuração do Doctrine
$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

$connection = DriverManager::getConnection($dbParams, $config);

// Criação do EntityManager
$entityManager = new EntityManager($connection, $config);