<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage\DoctrineStorage;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\BaseCase;

/**
 * Тест для хранилища, которое использует Doctrine.
 */
class DoctrineStorageTest extends BaseCase
{
    /**
     * Проверяет, что начало и завершение операций вызовут flush и clear.
     */
    public function testStartStop()
    {
        $em = $this->getMockBuilder(ObjectManager::class)->disableOriginalConstructor()->getMock();
        $em->expects($this->at(0))->method('flush');
        $em->expects($this->at(1))->method('clear');

        $doctrine = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $doctrine->method('getManager')->will($this->returnValue($em));

        $storage = new DoctrineStorage($doctrine);

        $storage->start();
        $storage->stop();
    }

    /**
     * Проверяет метод для добавления записей.
     */
    public function testInsert()
    {
        $object = new \stdClass;
        $object1 = new \stdClass;
        $object2 = new \stdClass;
        $object3 = new \stdClass;

        $em = $this->getMockBuilder(ObjectManager::class)->disableOriginalConstructor()->getMock();
        $em->expects($this->at(0))->method('persist')->with($this->identicalTo($object));
        $em->expects($this->at(1))->method('persist')->with($this->identicalTo($object1));
        $em->expects($this->at(2))->method('flush');
        $em->expects($this->at(3))->method('clear');
        $em->expects($this->at(4))->method('persist')->with($this->identicalTo($object2));
        $em->expects($this->at(5))->method('persist')->with($this->identicalTo($object3));
        $em->expects($this->at(6))->method('flush');
        $em->expects($this->at(7))->method('clear');

        $doctrine = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $doctrine->method('getManager')->will($this->returnValue($em));

        $storage = new DoctrineStorage($doctrine, 2);

        $storage->insert($object);
        $storage->insert($object1);
        $storage->insert($object2);
        $storage->insert($object3);
    }

    /**
     * Проверяет метод для удаления записей.
     */
    public function testDelete()
    {
        $object = new \stdClass;
        $mergedObject = new \stdClass;

        $em = $this->getMockBuilder(ObjectManager::class)->disableOriginalConstructor()->getMock();
        $em->expects($this->at(0))->method('merge')->with($this->identicalTo($object))->will($this->returnValue($mergedObject));
        $em->expects($this->at(1))->method('remove')->with($this->identicalTo($mergedObject));
        $em->expects($this->at(2))->method('flush');
        $em->expects($this->at(3))->method('clear');

        $doctrine = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $doctrine->method('getManager')->will($this->returnValue($em));

        $storage = new DoctrineStorage($doctrine);

        $storage->delete($object);
    }

    /**
     * Проверяет метод для обноления старой или создания новой записи.
     */
    public function testUpsert()
    {
        $object = new \stdClass;

        $em = $this->getMockBuilder(ObjectManager::class)->disableOriginalConstructor()->getMock();
        $em->expects($this->at(0))->method('merge')->with($this->identicalTo($object));
        $em->expects($this->at(1))->method('flush');
        $em->expects($this->at(2))->method('clear');

        $doctrine = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $doctrine->method('getManager')->will($this->returnValue($em));

        $storage = new DoctrineStorage($doctrine);

        $storage->upsert($object);
    }

    /**
     * Проверяет метод для очистки таблицы.
     */
    public function testTruncate()
    {
        $entityClassName = 'EntityTest';
        $name = 'NameTest';

        $query = $this->getMockBuilder(AbstractQuery::class)->disableOriginalConstructor()->getMock();
        $query->expects($this->once())->method('execute');

        $meta = $this->getMockBuilder(ClassMetadata::class)->disableOriginalConstructor()->getMock();
        $meta->method('getName')->will($this->returnValue($name));

        $em = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $em->expects($this->once())->method('getClassMetadata')
            ->with($this->identicalTo($entityClassName))
            ->will($this->returnValue($meta));
        $em->expects($this->once())->method('createQuery')
            ->with($this->identicalTo("DELETE {$name} p"))
            ->will($this->returnValue($query));

        $doctrine = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $doctrine->method('getManager')->will($this->returnValue($em));

        $storage = new DoctrineStorage($doctrine, 2);

        $storage->truncate($entityClassName);
    }
}
