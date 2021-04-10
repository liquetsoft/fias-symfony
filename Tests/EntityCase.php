<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests;

/**
 * Базовый класс для тестирования сущностей.
 */
abstract class EntityCase extends BaseCase
{
    /**
     * Возвращает инициированую сущность для тестирования.
     *
     * @return mixed
     */
    abstract protected function createEntity();

    /**
     * Возвращает массив с проверочными значениями для геттеров и сеттеров.
     *
     * В массиве должны быть три элемента: имя свойства, которое будет проверяться,
     * входящее значение для сеттера и то значение, которое мы получим из геттера.
     * В качестве третьего значения можно вернуть объект с исключением, в таком случае
     * тест будет ждать исключение в сеттере.
     *
     * @return array
     */
    abstract protected function accessorsProvider(): array;

    /**
     * Метод, который получает сущность и массив данных для тестов и проводит
     * тесты.
     */
    public function testAccessors(): void
    {
        $tests = $this->accessorsProvider();
        foreach ($tests as $testKey => $test) {
            if (!\is_array($test)) {
                $property = $testKey;
                $input = $output = $test;
            } elseif (\count($test) === 2) {
                list($property, $input) = $test;
                $output = $input;
            } else {
                list($property, $input, $output) = $test;
            }
            $this->assertAccessorsCase($property, $input, $output);
        }
    }

    /**
     * Проводит тест аццессоров по указанным параметрам в массиве.
     *
     * @param string $property
     * @param mixed  $input
     * @param mixed  $output
     * @param array  $testCase
     */
    protected function assertAccessorsCase(string $property, $input, $output): void
    {
        $setter = 'set' . ucfirst($property);
        $getter = 'get' . ucfirst($property);
        $entity = $this->createEntity();

        \call_user_func([$entity, $setter], $input);
        $toTest = \call_user_func([$entity, $getter]);
        $this->assertSame(
            $output,
            $toTest,
            "accessor pair {$setter}/{$getter} must returns expected value"
        );
    }
}
