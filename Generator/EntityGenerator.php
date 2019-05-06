<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\FiasBundle\Generator;

use Liquetsoft\Fias\Component\EntityRegistry\EntityRegistry;
use Liquetsoft\Fias\Component\EntityDescriptor\EntityDescriptor;
use Liquetsoft\Fias\Component\EntityField\EntityField;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Property;
use Nette\PhpGenerator\PsrPrinter;
use Nette\PhpGenerator\Method;
use SplFileInfo;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

/**
 * Объект, который генерирует классы сущностей doctrine на основани описаний
 * сущностей ФИАС.
 */
class EntityGenerator
{
    /**
     * @var EntityRegistry
     */
    protected $registry;

    /**
     * @param EntityRegistry $registry
     */
    public function __construct(EntityRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Создает классы сущностей в указанной папке с указанным пространством имен.
     *
     * @param SplFileInfo $dir
     * @param string      $namespace
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function run(SplFileInfo $dir, string $namespace = ''): void
    {
        $this->checkDir($dir);
        $unifiedNamespace = $this->unifyNamespace($namespace);

        try {
            $this->generate($dir, $unifiedNamespace);
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
     *
     * @throws Throwable
     */
    protected function generate(SplFileInfo $dir, string $namespace): void
    {
        $descriptors = $this->registry->getDescriptors();
        foreach ($descriptors as $descriptor) {
            $this->generateClassByDescriptor($descriptor, $dir, $namespace);
        }
    }

    /**
     * Создает php класс для указанного дескриптора.
     *
     * @param EntityDescriptor $descriptor
     * @param SplFileInfo      $dir
     * @param string           $namespace
     *
     * @throws Throwable
     */
    protected function generateClassByDescriptor(EntityDescriptor $descriptor, SplFileInfo $dir, string $namespace): void
    {
        $name = ucfirst($descriptor->getName());
        $fullPath = "{$dir->getPathname()}/{$name}.php";

        $phpFile = new PhpFile;
        $phpFile->setStrictTypes();

        $namespace = $phpFile->addNamespace($namespace);
        $this->decorateNamespace($namespace, $descriptor);

        $class = $namespace->addClass($name);
        $this->decorateClass($class, $descriptor);

        foreach ($descriptor->getFields() as $field) {
            $name = $this->unifyColumnName($field->getName());
            $setter = 'set' . ucfirst($name);
            $getter = 'get' . ucfirst($name);

            $this->decorateProperty($class->addProperty($name), $field);
            $this->decorateSetter($class->addMethod($setter), $field);
            $this->decorateGetter($class->addMethod($getter), $field);
        }

        file_put_contents($fullPath, (new PsrPrinter)->printFile($phpFile));
    }

    /**
     * Добавляет все необходимые импорты в пространство имен.
     *
     * @param PhpNamespace     $namespace
     * @param EntityDescriptor $descriptor
     */
    protected function decorateNamespace(PhpNamespace $namespace, EntityDescriptor $descriptor): void
    {
        $namespace->addUse('Doctrine\ORM\Mapping', 'ORM');
        foreach ($descriptor->getFields() as $field) {
            if ($field->getSubType() === 'uuid') {
                $namespace->addUse('Ramsey\Uuid\UuidInterface');
            }
            if ($field->getSubType() === 'date') {
                $namespace->addUse('DateTimeInterface');
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
        $indexes = [];
        foreach ($descriptor->getFields() as $field) {
            if ($field->isIndex()) {
                $indexes[] = $this->unifyColumnName($field->getName());
            }
        }

        $description = trim($descriptor->getDescription(), " \t\n\r\0\x0B.");
        if ($description) {
            $class->addComment("{$description}.\n");
        }

        $class->addComment("@ORM\MappedSuperclass\n");
    }

    /**
     * Добавляет все необходимые для свойства комментарии.
     *
     * @param Property    $property
     * @param EntityField $field
     */
    protected function decorateProperty(Property $property, EntityField $field): void
    {
        $type = trim($field->getType() . '_' . $field->getSubType(), ' _');
        switch ($type) {
            case 'int':
                $defaultValue = $field->isNullable() ? null : 0;
                $varType = 'int' . ($field->isNullable() ? '|null' : '');
                $column = '@ORM\Column(type="integer"' . ($field->isNullable() ? ', nullable=true' : '') . ')';
                if ($field->isPrimary()) {
                    $column = "@ORM\Id\n{$column}\n@ORM\GeneratedValue";
                }
                break;
            case 'string_uuid':
                $defaultValue = null;
                $varType = 'UuidInterface' . ($field->isNullable() ? '|null' : '');
                $column = '@ORM\Column(type="uuid"';
                $column .= $field->isNullable() ? ', nullable=true' : '';
                $column .= ')';
                if ($field->isPrimary()) {
                    $column = "@ORM\Id\n{$column}";
                }
                break;
            case 'string_date':
                $defaultValue = null;
                $varType = 'DateTimeInterface' . ($field->isNullable() ? '|null' : '');
                $column = '@ORM\Column(type="datetime"';
                $column .= $field->isNullable() ? ', nullable=true' : '';
                $column .= ')';
                break;
            default:
                $defaultValue = $field->isNullable() ? null : '';
                $varType = 'string' . ($field->isNullable() ? '|null' : '');
                $column = '@ORM\Column(type="string"';
                $column .= $field->getLength() ? ", length={$field->getLength()}" : '';
                $column .= $field->isNullable() ? ', nullable=true' : '';
                $column .= ')';
                if ($field->isPrimary()) {
                    $column = "@ORM\Id\n{$column}";
                }
                break;
        }

        $property->setValue($defaultValue);
        $property->setVisibility('protected');
        $property->addComment("{$column}\n");
        $property->addComment("@var {$varType}");
    }

    /**
     * Добавляет все необходимые для сеттера комментарии.
     *
     * @param Method      $method
     * @param EntityField $field
     */
    protected function decorateSetter(Method $method, EntityField $field): void
    {
        $type = trim($field->getType() . '_' . $field->getSubType(), ' _');
        switch ($type) {
            case 'int':
                $paramHint = 'int';
                break;
            case 'string_uuid':
                $paramHint = '\\Ramsey\\Uuid\\UuidInterface';
                break;
            case 'string_date':
                $paramHint = 'DateTimeInterface';
                break;
            default:
                $paramHint = 'string';
                break;
        }

        $parameterName = $this->unifyColumnName($field->getName());
        $parameter = $method->addParameter($parameterName);
        $parameter->setTypeHint($paramHint);
        if ($field->isNullable()) {
            $parameter->setNullable();
        }

        $method->setVisibility('public');
        $method->setReturnType('self');
        $method->setBody("\$this->{$parameterName} = \${$parameterName};\n\nreturn \$this;");
    }

    /**
     * Добавляет все необходимые для геттера комментарии.
     *
     * @param Method      $method
     * @param EntityField $field
     */
    protected function decorateGetter(Method $method, EntityField $field): void
    {
        $type = trim($field->getType() . '_' . $field->getSubType(), ' _');
        switch ($type) {
            case 'int':
                $returnHint = 'int';
                break;
            case 'string_uuid':
                $returnHint = '\\Ramsey\\Uuid\\UuidInterface';
                break;
            case 'string_date':
                $returnHint = 'DateTimeInterface';
                break;
            default:
                $returnHint = 'string';
                break;
        }

        $parameterName = $this->unifyColumnName($field->getName());

        $method->setVisibility('public');
        $method->setReturnType($returnHint);
        if ($field->isNullable()) {
            $method->setReturnNullable();
        }
        $method->setBody("return \$this->{$parameterName};");
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
        return trim(strtolower($name));
    }
}
