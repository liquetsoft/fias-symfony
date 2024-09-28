<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Generator;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Liquetsoft\Fias\Component\EntityDescriptor\EntityDescriptor;
use Liquetsoft\Fias\Component\EntityField\EntityField;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;
use Nette\PhpGenerator\PsrPrinter;
use Symfony\Component\Uid\Uuid;

/**
 * Объект, который генерирует классы сущностей doctrine на основани описаний
 * сущностей ФИАС.
 */
class EntityGenerator extends AbstractGenerator
{
    /**
     * Создает классы сущностей в указанной папке с указанным пространством имен.
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function run(\SplFileInfo $dir, string $namespace = ''): void
    {
        $this->checkDir($dir);
        $unifiedNamespace = $this->unifyNamespace($namespace);

        try {
            $this->generate($dir, $unifiedNamespace);
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
    protected function generate(\SplFileInfo $dir, string $namespace): void
    {
        $descriptors = $this->registry->getDescriptors();
        foreach ($descriptors as $descriptor) {
            $this->generateClassByDescriptor($descriptor, $dir, $namespace);
        }
    }

    /**
     * Создает php класс для указанного дескриптора.
     *
     * @throws \Throwable
     */
    protected function generateClassByDescriptor(EntityDescriptor $descriptor, \SplFileInfo $dir, string $namespace): void
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
     */
    protected function decorateNamespace(PhpNamespace $namespace, EntityDescriptor $descriptor): void
    {
        $namespace->addUse('Doctrine\ORM\Mapping', 'ORM');
        $namespace->addUse(\InvalidArgumentException::class);

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
        $description = ucfirst(trim($descriptor->getDescription(), " \t\n\r\0\x0B."));
        if ($description) {
            $class->addComment("{$description}.\n");
        }

        $class->addComment('@psalm-consistent-constructor');
        $class->addComment("@ORM\MappedSuperclass\n");
        $class->addAttribute(MappedSuperclass::class);

        $indexes = [];
        $indexPrefix = $this->unifyColumnName($descriptor->getName());
        foreach ($descriptor->getFields() as $field) {
            if ($field->isIndex()) {
                $column = $this->unifyColumnName($field->getName());
                $indexes[] = "@ORM\Index(name=\"{$indexPrefix}_{$column}_idx\", columns={\"{$column}\"})";
                $class->addAttribute(
                    Index::class,
                    [
                        'name' => "{$indexPrefix}_{$column}_idx",
                        'columns' => [$column],
                    ]
                );
            }
        }
        if ($indexes) {
            $table = '@ORM\Table(indexes={' . implode(',', $indexes) . '})';
            $class->addComment($table);
        }
    }

    /**
     * Добавляет все необходимые для свойства комментарии.
     */
    protected function decorateProperty(Property $property, EntityField $field): void
    {
        if ($field->getDescription()) {
            $description = ucfirst(rtrim($field->getDescription(), " \t\n\r\0\x0B.")) . '.';
            $property->addComment("{$description}\n");
        }

        $type = trim($field->getType() . '_' . $field->getSubType(), ' _');
        switch ($type) {
            case 'int':
                $defaultValue = $field->isNullable() ? null : 0;
                $property->addComment('@ORM\Column(type="integer"' . ($field->isNullable() ? ', nullable=true' : ', nullable=false') . ')');
                $property->addAttribute(
                    Column::class,
                    [
                        'type' => 'integer',
                        'nullable' => $field->isNullable(),
                    ]
                );
                if ($field->isPrimary()) {
                    $property->addComment('@ORM\Id');
                    $property->addAttribute(Id::class);
                }
                $property->setType('int');
                if ($field->isNullable()) {
                    $property->setNullable();
                }
                break;
            case 'string_uuid':
                $defaultValue = null;
                $property->addComment('@ORM\Column(type="uuid"' . ($field->isNullable() ? ', nullable=true' : ', nullable=false') . ')');
                $property->addAttribute(
                    Column::class,
                    [
                        'type' => 'uuid',
                        'nullable' => $field->isNullable(),
                    ]
                );
                if ($field->isPrimary()) {
                    $property->addComment('@ORM\Id');
                    $property->addAttribute(Id::class);
                }
                $property->setType(Uuid::class);
                $property->setNullable();
                break;
            case 'string_date':
                $defaultValue = null;
                $property->addComment('@ORM\Column(type="datetime_immutable"' . ($field->isNullable() ? ', nullable=true' : ', nullable=false') . ')');
                $property->addAttribute(
                    Column::class,
                    [
                        'type' => 'datetime_immutable',
                        'nullable' => $field->isNullable(),
                    ]
                );
                $property->setType(\DateTimeImmutable::class);
                $property->setNullable();
                break;
            default:
                $defaultValue = $field->isNullable() ? null : '';
                $column = '@ORM\Column(type="string"';
                $column .= $field->getLength() ? ", length={$field->getLength()}" : '';
                $column .= $field->isNullable() ? ', nullable=true' : ', nullable=false';
                $column .= ')';
                $property->addComment($column);
                $property->addAttribute(
                    Column::class,
                    [
                        'type' => 'string',
                        'nullable' => $field->isNullable(),
                        'length' => $field->getLength() ?: 255,
                    ]
                );
                if ($field->isPrimary()) {
                    $property->addComment('@ORM\Id');
                    $property->addAttribute(Id::class);
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
    }

    /**
     * Добавляет все необходимые для сеттера комментарии.
     */
    protected function decorateSetter(Method $method, EntityField $field): void
    {
        $type = trim($field->getType() . '_' . $field->getSubType(), ' _');
        switch ($type) {
            case 'int':
                $paramHint = 'int';
                break;
            case 'string_uuid':
                $paramHint = Uuid::class;
                break;
            case 'string_date':
                $paramHint = \DateTimeImmutable::class;
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
     */
    protected function decorateGetter(Method $method, EntityField $field): void
    {
        $type = trim($field->getType() . '_' . $field->getSubType(), ' _');
        $parameterName = $this->unifyColumnName($field->getName());

        $method->setVisibility('public');

        switch ($type) {
            case 'int':
                $method->setReturnType('int');
                break;
            case 'string_uuid':
                $method->setReturnType(Uuid::class);
                break;
            case 'string_date':
                $method->setReturnType(\DateTimeImmutable::class);
                break;
            default:
                $method->setReturnType('string');
                break;
        }

        if ($field->isNullable()) {
            $method->setReturnNullable();
        } elseif (!$field->isNullable() && ($type === 'string_uuid' || $type === 'string_date')) {
            $method->addBody("if (\$this->{$parameterName} === null) {");
            $method->addBody("    throw new InvalidArgumentException(\"Parameter '{$parameterName}' isn't set.\");");
            $method->addBody("}\n");
        }

        $method->addBody("return \$this->{$parameterName};");
    }
}
