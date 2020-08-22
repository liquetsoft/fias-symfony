<?php

use Liquetsoft\Fias\Component\EntityDescriptor\BaseEntityDescriptor;
use Liquetsoft\Fias\Component\EntityField\BaseEntityField;
use Liquetsoft\Fias\Component\EntityRegistry\ArrayEntityRegistry;
use Liquetsoft\Fias\Component\EntityRegistry\PhpArrayFileRegistry;
use Liquetsoft\Fias\Component\Helper\FileSystemHelper;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Generator\EntityGenerator;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Generator\TestGenerator;

$root = dirname(dirname(__DIR__));

require_once $root . '/vendor/autoload.php';

$dir = $root . '/Entity';
if (is_dir($dir)) {
    FileSystemHelper::remove(new SplFileInfo($dir));
}
mkdir($dir, 0777, true);

$testDir = $root . '/Tests/Entity';
if (is_dir($testDir)) {
    FileSystemHelper::remove(new SplFileInfo($testDir));
}
mkdir($testDir, 0777, true);

$defaultRegistry = new PhpArrayFileRegistry();
$registry = new ArrayEntityRegistry(array_merge($defaultRegistry->getDescriptors(), [
    new BaseEntityDescriptor([
        'name' => 'FiasVersion',
        'description' => 'Версия ФИАС',
        'xmlPath' => '//',
        'fields' => [
            new BaseEntityField([
                'name' => 'version',
                'type' => 'int',
                'description' => 'Номер версии ФИАС',
                'isNullable' => false,
                'isPrimary' => true,
            ]),
            new BaseEntityField([
                'name' => 'url',
                'type' => 'string',
                'description' => 'Ссылка для загрузки указанной версии ФИАС',
                'isNullable' => false,
            ]),
            new BaseEntityField([
                'name' => 'created',
                'type' => 'string',
                'subType' => 'date',
                'description' => 'Дата создания',
                'isNullable' => false,
            ]),
        ],
    ]),
]));

$dirObject = new SplFileInfo($dir);
$namespace = 'Liquetsoft\\Fias\\Symfony\\LiquetsoftFiasBundle\\Entity';
$generator = new EntityGenerator($registry);
$generator->run($dirObject, $namespace);

$testDirObject = new SplFileInfo($testDir);
$namespace = 'Liquetsoft\\Fias\\Symfony\\LiquetsoftFiasBundle\\Tests\\Entity';
$baseNamespace = 'Liquetsoft\\Fias\\Symfony\\LiquetsoftFiasBundle\\Entity';
$testGenerator = new TestGenerator($registry);
$testGenerator->run($testDirObject, $namespace, $baseNamespace);
