<?php 
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Dotenv\Dotenv;


require_once "vendor/autoload.php";

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env'); 

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: array(__DIR__."/src/models"),
    isDevMode: true,
);

// conntection to the mysql database from env file
$connection = DriverManager::getConnection([
    'driver' => 'pdo_mysql',
    'host' => $_ENV['DB_HOST'],
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
], $config);

// obtaining the entity manager
$entityManager = new EntityManager($connection, $config);
?>