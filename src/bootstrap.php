<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";


$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/models/noark5/v31/"
), $isDevMode);
$conn = array(
    'driver' => 'pdo_mysql',
    'user' => 'root',
    'password' => 'techno23',
    'dbname' => 'ephorteFEK_5H'
);

$entityManager = EntityManager::create($conn, $config);

//if ($entityManager.)
//print_r($entityManager);
?>