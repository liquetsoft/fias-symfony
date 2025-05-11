<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Generator;

use Liquetsoft\Fias\Component\EntityDescriptor\EntityDescriptor;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use Symfony\Component\Uid\Uuid;

/**
 * Объект, который генерирует классы тестов для сущностей doctrine на основани описаний
 * сущностей ФИАС.
 */
class TestGenerator extends AbstractGenerator
{
    /**
     * Создает классы сущностей в указанной папке с указанным пространством имен.
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function run(\SplFileInfo $dir, string $namespace = '', string $baseNamespace = ''): void
    {
        $this->checkDir($dir);
        $unifiedNamespace = $this->unifyNamespace($namespace);
        $unifiedBaseNamespace = $this->unifyNamespace($baseNamespace);

        try {
            $this->generate($dir, $unifiedNamespace, $unifiedBaseNamespace);
        } catch (\Throwable $e) {
            $message = 'Error while class generation.';
            throw new \RuntimeException($message, 0, $e);
        }
    }

    /**
     * Процесс генерации классов.
     *
     * @throws \Throwable
     */
    protected function generate(\SplFileInfo $dir, string $namespace, string $baseNamespace): void
    {
        $descriptors = $this->registry->getDescriptors();
        foreach ($descriptors as $descriptor) {
            $this->generateClassByDescriptor($descriptor, $dir, $namespace, $baseNamespace);
        }
    }

    /**
     * Создает php класс для указанного дескриптора.
     *
     * @throws \Throwable
     */
    protected function generateClassByDescriptor(EntityDescriptor $descriptor, \SplFileInfo $dir, string $namespace, string $baseNamespace): void
    {
        $baseName = $this->unifyClassName($descriptor->getName());
        $name = $this->unifyClassName($descriptor->getName()) . 'Test';
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
        $createEntityMethod->setReturnType('object');
        $createEntityMethod->addAttribute('Override');

        $accessors = "return [\n";
        foreach ($descriptor->getFields() as $field) {
            $name = $this->unifyColumnName($field->getName());
            $value = '"test string"';
            $type = trim($field->getType() . '_' . $field->getSubType(), ' _');
            if ($type === 'int') {
                $value = '123321';
            } elseif ($type === 'string_uuid') {
                $value = '$this->mock(Uuid::class)';
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
        $accessorsProviderMethod->addAttribute('Override');

        file_put_contents($fullPath, (new PsrPrinter())->printFile($phpFile));
    }

    /**
     * Добавляет все необходимые импорты в пространство имен.
     */
    protected function decorateNamespace(PhpNamespace $namespace, EntityDescriptor $descriptor, string $baseNamespace, string $baseName): void
    {
        $namespace->addUse(EntityCase::class);
        $namespace->addUse($baseNamespace . '\\' . $baseName);
        foreach ($descriptor->getFields() as $field) {
            if ($field->getSubType() === 'uuid') {
                $namespace->addUse(Uuid::class);
            }
            if ($field->getSubType() === 'date') {
                $namespace->addUse(\DateTimeImmutable::class);
            }
        }
    }

    /**
     * Добавляет всен необходимые для класса комментарии.
     */
    protected function decorateClass(ClassType $class, EntityDescriptor $descriptor): void
    {
        $class->setExtends(EntityCase::class);
        $description = trim($descriptor->getDescription(), " \t\n\r\0\x0B.");
        if ($description) {
            $class->addComment("Тест для сущности '{$description}'.\n\n@internal");
        }
    }
}
