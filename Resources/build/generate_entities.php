<?php

declare(strict_types=1);

use Liquetsoft\Fias\Component\EntityDescriptor\BaseEntityDescriptor;
use Liquetsoft\Fias\Component\EntityField\BaseEntityField;
use Liquetsoft\Fias\Component\EntityRegistry\ArrayEntityRegistry;
use Liquetsoft\Fias\Component\EntityRegistry\PhpArrayFileRegistry;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Generator\DenormalizerGenerator;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Generator\EntityGenerator;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Generator\TestGenerator;
use Marvin255\FileSystemHelper\FileSystemFactory;

$root = dirname(__DIR__, 2);

require_once $root . '/vendor/autoload.php';

$fs = FileSystemFactory::create();

$dir = $root . '/Entity';
$fs->mkdirIfNotExist($dir);
$fs->emptyDir($dir);

$testDir = $root . '/Tests/Entity';
$fs->mkdirIfNotExist($testDir);
$fs->emptyDir($testDir);

$serializerDir = $root . '/Serializer';
$fs->mkdirIfNotExist($serializerDir);

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

$serializerDirObject = new SplFileInfo($serializerDir);
$namespace = 'Liquetsoft\\Fias\\Symfony\\LiquetsoftFiasBundle\\Serializer';
$denormalizerGenerator = new DenormalizerGenerator($registry);
$denormalizerGenerator->run($serializerDirObject, $namespace);
