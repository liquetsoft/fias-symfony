<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Generator;

use DateTimeInterface;
use InvalidArgumentException;
use Liquetsoft\Fias\Component\EntityDescriptor\EntityDescriptor;
use Liquetsoft\Fias\Component\EntityField\EntityField;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;
use Nette\PhpGenerator\PsrPrinter;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use SplFileInfo;
use Throwable;

/**
 * Объект, который генерирует классы сущностей doctrine на основани описаний
 * сущностей ФИАС.
 */
class EntityGenerator extends AbstractGenerator
{
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
        $name = $this->unifyClassName($descriptor->getName());
        $fullPath = "{$dir->getPathname()}/{$name}.php";

        $phpFile = new PhpFile();
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

        file_put_contents($fullPath, (new PsrPrinter())->printFile($phpFile));
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
                $namespace->addUse(UuidInterface::class);
            }
            if ($field->getSubType() === 'date') {
                $namespace->addUse(DateTimeInterface::class);
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
        $description = ucfirst(trim($descriptor->getDescription(), " \t\n\r\0\x0B."));
        if ($description) {
            $class->addComment("{$description}.\n");
        }

        $class->addComment("@ORM\MappedSuperclass\n");

        $indexes = [];
        $indexPrefix = $this->unifyColumnName($descriptor->getName());
        foreach ($descriptor->getFields() as $field) {
            if ($field->isIndex()) {
                $column = $this->unifyColumnName($field->getName());
                $indexes[] = "@ORM\Index(name=\"{$indexPrefix}_{$column}_idx\", columns={\"{$column}\"})";
            }
        }
        if ($indexes) {
            $table = '@ORM\Table(indexes={' . implode(',', $indexes) . '})';
            $class->addComment($table);
        }
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
                $column = '@ORM\Column(type="integer"' . ($field->isNullable() ? ', nullable=true' : ', nullable=false') . ')';
                if ($field->isPrimary()) {
                    $column = "@ORM\Id\n{$column}";
                }
                $property->setType('int');
                if ($field->isNullable()) {
                    $property->setNullable();
                }
                break;
            case 'string_uuid':
                $defaultValue = null;
                $varType = 'UuidInterface|null';
                $column = '@ORM\Column(type="uuid"';
                $column .= $field->isNullable() ? ', nullable=true' : ', nullable=false';
                $column .= ')';
                if ($field->isPrimary()) {
                    $column = "@ORM\Id\n{$column}";
                }
                $property->setType(UuidInterface::class);
                $property->setNullable();
                break;
            case 'string_date':
                $defaultValue = null;
                $varType = 'DateTimeInterface|null';
                $column = '@ORM\Column(type="datetime"';
                $column .= $field->isNullable() ? ', nullable=true' : ', nullable=false';
                $column .= ')';
                $property->setType(DateTimeInterface::class);
                $property->setNullable();
                break;
            default:
                $defaultValue = $field->isNullable() ? null : '';
                $varType = 'string' . ($field->isNullable() ? '|null' : '');
                $column = '@ORM\Column(type="string"';
                $column .= $field->getLength() ? ", length={$field->getLength()}" : '';
                $column .= $field->isNullable() ? ', nullable=true' : ', nullable=false';
                $column .= ')';
                if ($field->isPrimary()) {
                    $column = "@ORM\Id\n{$column}";
                }
                $property->setType('string');
                if ($field->isNullable()) {
                    $property->setNullable();
                }
                break;
        }

        $property->setValue($defaultValue);
        $property->setVisibility('protected');
        $property->setInitialized();
        if ($field->getDescription()) {
            $description = ucfirst(rtrim($field->getDescription(), " \t\n\r\0\x0B.")) . '.';
            $property->addComment("{$description}\n");
        }
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
                $paramHint = UuidInterface::class;
                break;
            case 'string_date':
                $paramHint = DateTimeInterface::class;
                break;
            default:
                $paramHint = 'string';
                break;
        }

        $parameterName = $this->unifyColumnName($field->getName());
        $parameter = $method->addParameter($parameterName);
        $parameter->setType($paramHint);
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
                $isNullable = $field->isNullable();
                break;
            case 'string_uuid':
                $returnHint = UuidInterface::class;
                $isNullable = true;
                break;
            case 'string_date':
                $returnHint = DateTimeInterface::class;
                $isNullable = true;
                break;
            default:
                $returnHint = 'string';
                $isNullable = $field->isNullable();
                break;
        }

        $parameterName = $this->unifyColumnName($field->getName());

        $method->setVisibility('public');
        $method->setReturnType($returnHint);
        if ($isNullable) {
            $method->setReturnNullable();
        }
        $method->setBody("return \$this->{$parameterName};");
    }
}
