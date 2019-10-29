<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Pipeline\Task;

use Liquetsoft\Fias\Component\Pipeline\State\ArrayState;
use Liquetsoft\Fias\Component\Pipeline\Task\Task;
use Symfony\Component\HttpKernel\Kernel;
use InvalidArgumentException;
use RuntimeException;
use Exception;

/**
 * Объект с логикой коллбэка для параллельного выполнения.
 * Инициирует свое ядро symfony и запускает задачу на исполнение.
 */
class ParallelInsertCallback
{
    /**
     * @var string
     * @psalm-var class-string
     */
    protected $kernelClass;

    /**
     * @var array
     */
    protected $params;

    /**
     * @param string $kernelClass
     * @param array  $params
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $kernelClass, array $params)
    {
        if (empty($kernelClass)) {
            throw new InvalidArgumentException('Kernel class in not set.');
        }
        if (!class_exists($kernelClass) || !is_subclass_of($kernelClass, Kernel::class)) {
            throw new InvalidArgumentException(
                "Kernel class must be a valid class name that extends '" . Kernel::class . "'."
            );
        }

        $this->kernelClass = $kernelClass;
        $this->params = $params;
    }

    /**
     * Запускает коллбэк на исполнение.
     *
     * @throws Exception
     */
    public function run(): void
    {
        $kernelClass = $this->kernelClass;

        /** @var Kernel $kernel */
        $kernel = new $kernelClass('production', false);
        $kernel->boot();

        $container = $kernel->getContainer();
        if (!$container) {
            throw new RuntimeException(
                "Can't load DI container from symfony."
            );
        }

        $insertTask = $container->get('liquetsoft_fias.task.data.insert');
        if (!($insertTask instanceof Task)) {
            throw new RuntimeException(
                "Can't find 'liquetsoft_fias.task.data.insert' task."
            );
        }

        $deleteTask = $container->get('liquetsoft_fias.task.data.delete');
        if (!($deleteTask instanceof Task)) {
            throw new RuntimeException(
                "Can't find 'liquetsoft_fias.task.data.delete' task."
            );
        }

        $state = $this->initState();
        $insertTask->run($state);
        $deleteTask->run($state);
    }

    /**
     * Создает и инициирует объект состояния.
     *
     * @return ArrayState
     */
    protected function initState(): ArrayState
    {
        $state = new ArrayState();

        foreach ($this->params as $paramName => $paramValue) {
            $state->setAndLockParameter($paramName, $paramValue);
        }

        return $state;
    }
}
