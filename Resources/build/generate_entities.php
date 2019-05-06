<?php

use Liquetsoft\Fias\Symfony\FiasBundle\Generator\EntityGenerator;
use Liquetsoft\Fias\Component\EntityRegistry\YamlEntityRegistry;

$root = dirname(dirname(__DIR__));
$entitiesYaml = $root . '/vendor/liquetsoft/fias-component/resources/fias_entities.yaml';

require_once $root . '/vendor/autoload.php';

$dir = $root . '/Entity';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$registry = new YamlEntityRegistry($entitiesYaml);
$generator = new EntityGenerator($registry);

$dirObject = new SplFileInfo($dir);
$namespace = 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\Entity';
$generator->run($dirObject, $namespace);
