<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Generator;

use InvalidArgumentException;
use Liquetsoft\Fias\Component\EntityDescriptor\EntityDescriptor;
use Liquetsoft\Fias\Component\EntityRegistry\EntityRegistry;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use RuntimeException;
use SplFileInfo;
use Throwable;

/**
 * Объект, который генерирует классы тестов для сущностей doctrine на основани описаний
 * сущностей ФИАС.
 */
class TestGenerator
{
    protected EntityRegistry $registry;

    public function __construct(EntityRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Создает классы сущностей в указанной папке с указанным пространством имен.
     *
     * @param SplFileInfo $dir
     * @param string      $namespace
     * @param string      $baseNamespace
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function run(SplFileInfo $dir, string $namespace = '', string $baseNamespace = ''): void
    {
        $this->checkDir($dir);
        $unifiedNamespace = $this->unifyNamespace($namespace);
        $unifiedBaseNamespace = $this->unifyNamespace($baseNamespace);

        try {
            $this->generate($dir, $unifiedNamespace, $unifiedBaseNamespace);
        } catch (Throwable $e) {
            $message = 'Error while class generation.';
            throw new RuntimeException($message, 0, $e);
        }
    }

    /**
     * Процесс генерации классов.
     *
     * @param SplFileInfo $dir
     * @param string      $namespace
     * @param string      $baseNamespace
     *
     * @throws Throwable
     */
    protected function generate(SplFileInfo $dir, string $namespace, string $baseNamespace): void
    {
        $descriptors = $this->registry->getDescriptors();
        foreach ($descriptors as $descriptor) {
            $this->generateClassByDescriptor($descriptor, $dir, $namespace, $baseNamespace);
        }
    }

    /**
     * Создает php класс для указанного дескриптора.
     *
     * @param EntityDescriptor $descriptor
     * @param SplFileInfo      $dir
     * @param string           $namespace
     * @param string           $baseNamespace
     *
     * @throws Throwable
     */
    protected function generateClassByDescriptor(EntityDescriptor $descriptor, SplFileInfo $dir, string $namespace, string $baseNamespace): void
    {
        $baseName = ucfirst($descriptor->getName());
        $name = ucfirst($descriptor->getName()) . 'Test';
        $fullPath = "{$dir->getPathname()}/{$name}.php";

        $phpFile = new PhpFile();
        $phpFile->setStrictTypes();

        $namespace = $phpFile->addNamespace($namespace);
        $this->decorateNamespace($namespace, $descriptor, $baseNamespace, $baseName);

        $class = $namespace->addClass($name);
        $this->decorateClass($class, $descriptor);

        $createEntityMethod = $class->addMethod('createEntity');
        $createEntityMethod->addComment("{@inheritDoc}\n");
        $createEntityMethod->setVisibility('protected');
        $createEntityMethod->setBody("return new {$baseName}();");

        $accessors = "return [\n";
        foreach ($descriptor->getFields() as $field) {
            $name = $this->unifyColumnName($field->getName());
            $value = '$this->createFakeData()->word';
            $type = trim($field->getType() . '_' . $field->getSubType(), ' _');
            if ($type === 'int') {
                $value = '$this->createFakeData()->numberBetween(1, 1000000)';
            } elseif ($type === 'string_uuid') {
                $value = '$this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock()';
            } elseif ($type === 'string_date') {
                $value = 'new DateTimeImmutable()';
            }
            $accessors .= "    '{$name}' => {$value},\n";
        }
        $accessors .= '];';
        $accessorsProviderMethod = $class->addMethod('accessorsProvider');
        $accessorsProviderMethod->setVisibility('protected');
        $accessorsProviderMethod->setReturnType('array');
        $accessorsProviderMethod->setBody($accessors);
        $accessorsProviderMethod->addComment("{@inheritDoc}\n");

        file_put_contents($fullPath, (new PsrPrinter())->printFile($phpFile));
    }

    /**
     * Добавляет все необходимые импорты в пространство имен.
     *
     * @param PhpNamespace     $namespace
     * @param EntityDescriptor $descriptor
     * @param string           $baseNamespace
     * @param string           $baseName
     */
    protected function decorateNamespace(PhpNamespace $namespace, EntityDescriptor $descriptor, string $baseNamespace, string $baseName): void
    {
        $namespace->addUse('Liquetsoft\\Fias\\Symfony\\LiquetsoftFiasBundle\\Tests\\EntityCase');
        $namespace->addUse($baseNamespace . '\\' . $baseName);
        foreach ($descriptor->getFields() as $field) {
            if ($field->getSubType() === 'uuid') {
                $namespace->addUse('Ramsey\Uuid\UuidInterface');
            }
            if ($field->getSubType() === 'date') {
                $namespace->addUse('DateTimeImmutable');
            }
        }
    }

    /**
     * Добавляет всен необходимые для класса комментарии.
     *
     * @param ClassType        $class
     * @param EntityDescriptor $descriptor
     */
    protected function decorateClass(ClassType $class, EntityDescriptor $descriptor): void
    {
        $class->setExtends('Liquetsoft\\Fias\\Symfony\\LiquetsoftFiasBundle\\Tests\\EntityCase');
        $description = trim($descriptor->getDescription(), " \t\n\r\0\x0B.");
        if ($description) {
            $class->addComment("Тест для сущности '{$description}'.\n\n@internal");
        }
    }

    /**
     * Проверяет, что каталог существует и доступен на запись.
     *
     * @param SplFileInfo $dir
     *
     * @throws InvalidArgumentException
     */
    protected function checkDir(SplFileInfo $dir): void
    {
        if (!$dir->isDir() || !$dir->isWritable()) {
            throw new InvalidArgumentException(
                "Destination folder '" . $dir->getPathname() . "' isn't writable or doesn't exist."
            );
        }
    }

    /**
     * Приводит пространсва имен к единообразному виду.
     *
     * @param string $namespace
     *
     * @return string
     */
    protected function unifyNamespace(string $namespace): string
    {
        return trim($namespace, " \t\n\r\0\x0B\\");
    }

    /**
     * Приводит имена колонок к единообразному виду.
     *
     * @param string $name
     *
     * @return string
     */
    protected function unifyColumnName(string $name): string
    {
        return trim(strtolower(str_replace('_', '', $name)));
    }
}
