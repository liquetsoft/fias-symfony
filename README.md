ФИАС bundle
===========

[![Latest Stable Version](https://poser.pugx.org/liquetsoft/fias-symfony/v/stable.png)](https://packagist.org/packages/liquetsoft/fias-symfony)
[![Total Downloads](https://poser.pugx.org/liquetsoft/fias-symfony/downloads.png)](https://packagist.org/packages/liquetsoft/fias-symfony)
[![License](https://poser.pugx.org/liquetsoft/fias-symfony/license.svg)](https://packagist.org/packages/liquetsoft/fias-symfony)
[![Build Status](https://travis-ci.org/liquetsoft/fias-symfony.svg?branch=master)](https://travis-ci.org/liquetsoft/fias-symfony)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/liquetsoft/fias-symfony/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/liquetsoft/fias-symfony/?branch=master)

Бандл symfony для установки данных из [ФИАС](https://fias.nalog.ru/).

Для установки ФИАС используются xml-файлы, ссылки на которые предоставляются SOAP-сервисом информирования ФИАС.



Установка
---------

Бандл устанавливается с помощью `composer` и следует стандартной структуре, поэтому на `symfony >=4.2` устанавливается автоматически.

1. Установить пакет с помощью composer:

    ```bash
    composer require liquetsoft/fias-symfony
    ```

2. В силу огромных размеров данных ФИАС, сущности `Doctrine` не регистрируются сразу в проекте. Для каждой предоставлен `MappedSuperclass`, с помощью которого можно получать обновления полей исключительно для тех сущностей, которые требуются проекту. Кроме того, это позволит дополнить или изменить структуру таблиц. Например, для добавления списка адресов:

    ```php
    <?php
    // src/Entity/AddressObject.php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddressObject as LiquetsoftAddressObject;

    /**
     * Адреса.
     *
     * @ORM\Entity(repositoryClass="App\Repository\AddressObjectRepository")
     */
    class AddressObject extends LiquetsoftAddressObject
    {
    }
    ```

    Список доступных суперклассов:

    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ActualStatus`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddressObject`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddressObjectType`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\CenterStatus`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\CurrentStatus`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\EstateStatus`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\FlatType`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\House`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocument`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocumentType`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\OperationStatus`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Room`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\RoomType`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Stead`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\StructureStatus`

3. Отдельно следует создать сущность для управления версиями ФИАС, установленными на проекте, которая используется для обновления:

    ```php
    <?php
    // src/Entity/FiasVersion.php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\FiasVersion as LiquetsoftFiasVersion;

    /**
     * Сущность, которая хранит текущую версию ФИАС.
     *
     * @ORM\Entity
     */
    class FiasVersion extends LiquetsoftFiasVersion
    {
    }
    ```

4. После создания всех сущностей, следует создать соответствующие им миграции, и применить их:

    ```bash
    bin/console make:migration
    ```

    ```bash
    bin/console doctrine:migration:migrate
    ```

5. Необходимо указать бандлу какие именно сущности используются (те сущности, для которых не указан класс конвертации использоваться не будут) и в какие объекты конвертируются (важно понимать, что сущность на стороне проекта может быть любой, даже не унаследованной от одного из суперклассов, стандартный сериализатор symfony попробует преобразовать xml в указанный объект):

    ```yaml
    # config/packages/liquetsoft_fias.yaml
    liquetsoft_fias:
        # сущность, которая хранит версии ФИАС
        version_manager_entity: App\Entity\FiasVersion
        # массив, в котором указывается какие сущности в какой объект преобразовывать
        entity_bindings:
            ActualStatus: App\Entity\ActualStatus
            AddressObject: App\Entity\AddressObject
            AddressObjectType: App\Entity\AddressObjectType
            CenterStatus: App\Entity\CenterStatus
            CurrentStatus: App\Entity\CurrentStatus
            EstateStatus: App\Entity\EstateStatus
            FlatType: App\Entity\FlatType
            House: App\Entity\House
            NormativeDocument: App\Entity\NormativeDocument
            NormativeDocumentType: App\Entity\NormativeDocumentType
            OperationStatus: App\Entity\OperationStatus
            Room: App\Entity\Room
            RoomType: App\Entity\RoomType
            Stead: App\Entity\Stead
            StructureStatus: App\Entity\StructureStatus
    ```

7. По умолчанию для записи используется `Doctrine`, что может быть довольно медленно, хоть и дает возможность использовать все преимущества `Doctrine`, например, события. В качестве альтернативы предлагается использовать `bulk insert`, он значительно быстрее, но не использует события:

    ```yaml
    # config/services.yaml
    services:
        # заменяем сервис для записи на сервис, который использует bulk insert
        liquetsoft_fias.storage.service:
            class: Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage\BulkInsertDoctrineStorage
            arguments:
                - '@doctrine.orm.entity_manager'
                - '%liquetsoft_fias.insert_batch_count%'
                - '@logger'
    ```

8. Поскольку для записи в БД используется `Doctrine`, нужно отключить логгирование запросов, иначе скрипт падает с переполнением памяти:

    ```yaml
    # config/packages/doctrine.yaml
    doctrine:
        dbal:
            logging: false # отключаем логгирование
            profiling: false # отключаем профилирование
    ```

Для примера, все шаги установки проделаны в [тестовом проекте](https://github.com/liquetsoft/fias-symfony-example), который реализует простейшее REST API с использованием [API platform](https://api-platform.com/).



Использование
-------------

Бандл определяет две значимых команды консоли:

1. Установка ФИАС с ноля

    ```bash
    bin/console liquetsoft:fias:install
    ```

2. Обновление ФИАС через дельту

    ```bash
    bin/console liquetsoft:fias:update
    ```

Соответственно, установка запускается только в первый раз, а обновление следует поставить в качестве задачи для `cron`.
