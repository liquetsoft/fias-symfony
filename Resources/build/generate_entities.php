<?php

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Generator\EntityGenerator;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Generator\TestGenerator;
use Liquetsoft\Fias\Component\EntityRegistry\YamlEntityRegistry;

$root = dirname(dirname(__DIR__));
$entitiesYaml = $root . '/vendor/liquetsoft/fias-component/resources/fias_entities.yaml';

require_once $root . '/vendor/autoload.php';

$dir = $root . '/Entity';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$testDir = $root . '/Tests/Entity';
if (!is_dir($testDir)) {
    mkdir($testDir, 0777, true);
}

$registry = new YamlEntityRegistry($entitiesYaml);

$dirObject = new SplFileInfo($dir);
$namespace = 'Liquetsoft\\Fias\\Symfony\\LiquetsoftFiasBundle\\Entity';
$generator = new EntityGenerator($registry);
$generator->run($dirObject, $namespace);

$testDirObject = new SplFileInfo($testDir);
$namespace = 'Liquetsoft\\Fias\\Symfony\\LiquetsoftFiasBundle\\Tests\\Entity';
$baseNamespace = 'Liquetsoft\\Fias\\Symfony\\LiquetsoftFiasBundle\\Entity';
$testGenerator = new TestGenerator($registry);
$testGenerator->run($testDirObject, $namespace, $baseNamespace);
