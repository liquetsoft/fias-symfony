<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Generator;

use Liquetsoft\Fias\Component\EntityDescriptor\EntityDescriptor;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpLiteral;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Объект, который генерирует класс денормализатора.
 */
class DenormalizerGenerator extends AbstractGenerator
{
    /**
     * Создает объект денормализатора для базовых сущностей.
     *
     * @param \SplFileInfo $dir
     * @param string       $namespace
     */
    public function run(\SplFileInfo $dir, string $namespace): void
    {
        $name = 'CompiledEntitesDenormalizer';
        $fullPath = "{$dir->getPathname()}/{$name}.php";

        $phpFile = new PhpFile();
        $phpFile->setStrictTypes();

        $namespace = $phpFile->addNamespace($namespace);
        $this->decorateNamespace($namespace);

        $class = $namespace->addClass($name);
        $class->addImplement(DenormalizerInterface::class)
            ->addImplement(DenormalizerAwareInterface::class)
            ->addTrait(DenormalizerAwareTrait::class)
        ;
        $this->decorateClass($class);

        file_put_contents($fullPath, (new PsrPrinter())->printFile($phpFile));
    }

    /**
     * Добавляет все необходимые импорты в пространство имен.
     *
     * @param PhpNamespace $namespace
     */
    protected function decorateNamespace(PhpNamespace $namespace): void
    {
        $namespace->addUse(DenormalizerInterface::class);
        $namespace->addUse(AbstractNormalizer::class);
        $namespace->addUse(InvalidArgumentException::class);
        $namespace->addUse(DenormalizerAwareInterface::class);
        $namespace->addUse(DenormalizerAwareTrait::class);

        $descriptors = $this->getRegistry()->getDescriptors();
        foreach ($descriptors as $descriptor) {
            $namespace->addUse($this->createModelClass($descriptor));
            foreach ($descriptor->getFields() as $field) {
                if ($field->getSubType() === 'date') {
                    $namespace->addUse(\DateTimeImmutable::class);
                } elseif ($field->getSubType() === 'uuid') {
                    $namespace->addUse(Uuid::class);
                }
            }
        }
    }

    /**
     * Добавляет в класс все необходимые методы и константы.
     *
     * @param ClassType $class
     */
    protected function decorateClass(ClassType $class): void
    {
        $class->addComment('Скомпилированный класс для денормализации сущностей ФИАС в модели.');

        $compiledDataSet = 'fias_compiled_data_set';
        $supportsBody = "return empty(\$context['{$compiledDataSet}'])\n    && (\n";
        $denormalizeBody = '$data = \\is_array($data) ? $data : [];' . "\nunset(\$data['#']);\n";
        $denormalizeBody .= '$type = trim($type, " \t\n\r\0\x0B/");' . "\n\n";
        $denormalizeBody .= "\$entity = \$context[AbstractNormalizer::OBJECT_TO_POPULATE] ?? new \$type();\n\n";
        $descriptors = $this->registry->getDescriptors();
        foreach ($descriptors as $key => $descriptor) {
            $className = $this->unifyClassName($descriptor->getName());
            $denormalizeBody .= ($key > 0 ? ' else' : '') . "if (\$entity instanceof {$className}) {\n";
            $denormalizeBody .= "        \$data = \$this->setDataTo{$className}Entity(\$entity, \$data);\n";
            $denormalizeBody .= '}';
            $supportsBody .= ($key > 0 ? '        || ' : '        ') . "is_subclass_of(\$type, {$className}::class)\n";
        }
        $supportsBody .= "    )\n;";
        $denormalizeBody .= " else {\n";
        $denormalizeBody .= "    \$message = sprintf(\"Can't find data extractor for '%s' type.\", \$type);\n";
        $denormalizeBody .= "    throw new InvalidArgumentException(\$message);\n";
        $denormalizeBody .= "}\n\n";
        $denormalizeBody .= "if (!empty(\$data) && \$this->denormalizer) {\n";
        $denormalizeBody .= "    \$context[AbstractNormalizer::OBJECT_TO_POPULATE] = \$entity;\n";
        $denormalizeBody .= "    \$context['{$compiledDataSet}'] = true;\n";
        $denormalizeBody .= "    \$entity = \$this->denormalizer->denormalize(\$data, \$type, \$format, \$context);\n";
        $denormalizeBody .= "}\n\n";
        $denormalizeBody .= 'return $entity;';

        $supports = $class->addMethod('supportsDenormalization')
            ->setReturnType('bool')
            ->addComment("{@inheritDoc}\n")
            ->addComment('@psalm-suppress MissingParamType')
            ->setVisibility('public')
            ->setBody($supportsBody);
        $supports->addParameter('data');
        $supports->addParameter('type')->setType('string');
        $supports->addParameter('format', new PhpLiteral('null'))->setType('string');
        $supports->addParameter('context', new PhpLiteral('[]'))->setType('array');

        $denormalize = $class->addMethod('denormalize')
            ->addComment("{@inheritDoc}\n")
            ->addComment('@psalm-suppress InvalidStringClass')
            ->addComment("@psalm-suppress RedundantConditionGivenDocblockType\n")
            ->setVisibility('public')
            ->setBody($denormalizeBody);
        $denormalize->addParameter('data');
        $denormalize->addParameter('type')->setType('string');
        $denormalize->addParameter('format', new PhpLiteral('null'))->setType('string');
        $denormalize->addParameter('context', new PhpLiteral('[]'))->setType('array');

        foreach ($descriptors as $descriptor) {
            $className = $this->unifyClassName($descriptor->getName());
            $entityMethod = $class->addMethod("setDataTo{$className}Entity");
            $this->decorateEntityDataSetter($entityMethod, $descriptor);
        }
    }

    /**
     * Создает метод для денормализации одной конкретной модели.
     *
     * @param Method           $method
     * @param EntityDescriptor $descriptor
     */
    protected function decorateEntityDataSetter(Method $method, EntityDescriptor $descriptor): void
    {
        $className = $this->unifyClassName($descriptor->getName());
        $fqcn = $this->createModelClass($descriptor);

        $body = '';
        foreach ($descriptor->getFields() as $field) {
            $column = $this->unifyColumnName($field->getName());
            $setterName = 'set' . ucfirst($column);
            $xmlAttribute = '@' . strtoupper($column);
            $type = trim($field->getType() . '_' . $field->getSubType(), ' _');
            switch ($type) {
                case 'int':
                    $varType = "(int) \$data['{$xmlAttribute}']";
                    break;
                case 'string_date':
                    $varType = "new DateTimeImmutable((string) \$data['{$xmlAttribute}'])";
                    break;
                case 'string_uuid':
                    $varType = "Uuid::fromString((string) \$data['{$xmlAttribute}'])";
                    break;
                default:
                    $varType = "(string) \$data['{$xmlAttribute}']";
                    break;
            }
            if ($field->isNullable()) {
                $varType = "\$data['{$xmlAttribute}'] === null || \$data['{$xmlAttribute}'] === '' ? null : {$varType}";
            }
            $body .= "if (array_key_exists('{$xmlAttribute}', \$data)) {\n";
            $body .= "    \$entity->{$setterName}({$varType});\n";
            $body .= "    unset(\$data['{$xmlAttribute}']);\n";
            $body .= "}\n";
        }

        $body .= "\nreturn \$data;";

        $method->addComment("Наполняет сущность '{$className}' данными и возвращает те, которые не были использованы.\n");
        $method->addComment("@param {$className} \$entity\n");
        $method->addComment("@param array \$data\n");
        $method->addComment("@return array\n");
        $method->addParameter('entity')->setType($fqcn);
        $method->addParameter('data')->setType('array');
        $method->setVisibility('private');
        $method->setReturnType('array');
        $method->setBody($body);
    }

    /**
     * Создает имя класса для модели дескриптора.
     *
     * @param EntityDescriptor $descriptor
     *
     * @return string
     */
    protected function createModelClass(EntityDescriptor $descriptor): string
    {
        return 'Liquetsoft\\Fias\\Symfony\\LiquetsoftFiasBundle\\Entity\\'
            . $this->unifyClassName($descriptor->getName());
    }
}
