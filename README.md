ФИАС bundle
===========

[![Latest Stable Version](https://poser.pugx.org/liquetsoft/fias-symfony/v)](https://packagist.org/packages/liquetsoft/fias-symfony)
[![Total Downloads](https://poser.pugx.org/liquetsoft/fias-symfony/downloads)](https://packagist.org/packages/liquetsoft/fias-symfony)
[![License](https://poser.pugx.org/liquetsoft/fias-symfony/license)](https://packagist.org/packages/liquetsoft/fias-symfony)
[![Build Status](https://github.com/liquetsoft/fias-symfony/workflows/liquetsoft_fias/badge.svg)](https://github.com/liquetsoft/fias-symfony/actions?query=workflow%3A%22liquetsoft_fias%22)

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
    use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddrObj as LiquetsoftAddrObj;

    /**
     * Адреса.
     */
    #[ORM\Entity(repositoryClass: AddressObjectRepository::class)]
    class AddrObj extends LiquetsoftAddrObj
    {
    }
    ```

    Список доступных суперклассов:

    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddrObj`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddrObjDivision`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddrObjTypes`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AdmHierarchy`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Apartments`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ApartmentTypes`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Carplaces`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ChangeHistory`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Houses`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\HouseTypes`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\MunHierarchy`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocs`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocsKinds`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocsTypes`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ObjectLevels`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\OperationTypes`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Param`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ParamTypes`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ReestrObjects`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Rooms`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\RoomTypes`
    * `Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Steads`

3. Отдельно следует создать сущность для управления версиями ФИАС, установленными на проекте, которая используется для обновления:

    ```php
    <?php
    // src/Entity/FiasVersion.php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\FiasVersion as LiquetsoftFiasVersion;

    /**
     * Сущность, которая хранит текущую версию ФИАС.
     */
    #[ORM\Entity()]
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
            ADDR_OBJ: App\Entity\AddrObj
            ADDR_OBJ_DIVISION: App\Entity\AddrObjDivision
            ADDR_OBJ_TYPES: App\Entity\AddrObjTypes
            ADM_HIERARCHY: App\Entity\AdmHierarchy
            APARTMENTS: App\Entity\Apartments
            APARTMENT_TYPES: App\Entity\ApartmentTypes
            CARPLACES: App\Entity\Carplaces
            CHANGE_HISTORY: App\Entity\ChangeHistory
            HOUSES: App\Entity\Houses
            HOUSE_TYPES: App\Entity\HouseTypes
            MUN_HIERARCHY: App\Entity\MunHierarchy
            NORMATIVE_DOCS: App\Entity\NormativeDocs
            NORMATIVE_DOCS_KINDS: App\Entity\NormativeDocsKinds
            NORMATIVE_DOCS_TYPES: App\Entity\NormativeDocsTypes
            OBJECT_LEVELS: App\Entity\ObjectLevels
            OPERATION_TYPES: App\Entity\OperationTypes
            PARAM: App\Entity\Param
            PARAM_TYPES: App\Entity\ParamTypes
            REESTR_OBJECTS: App\Entity\ReestrObjects
            ROOMS: App\Entity\Rooms
            ROOM_TYPES: App\Entity\RoomTypes
            STEADS: App\Entity\Steads
    ```

6. Поскольку для записи в БД используется `Doctrine`, нужно отключить логгирование запросов, иначе скрипт падает с переполнением памяти:

    ```yaml
    # config/packages/doctrine.yaml
    doctrine:
        dbal:
            logging: false # отключаем логгирование
            profiling: false # отключаем профилирование
    ```



Использование
-------------

Бандл определяет несколько значимых команды консоли:

1. Установка ФИАС с ноля

    ```bash
    bin/console liquetsoft:fias:install
    ```

2. Обновление ФИАС через дельту (установка запускается только в первый раз, а обновление следует поставить в качестве задачи для `cron`)

    ```bash
    bin/console liquetsoft:fias:update
    ```

3. Текущий статус серверов ФИАС (сервис информирования или сервер с файлами могут быть недоступны по тем или иным причинам)

    ```bash
    bin/console liquetsoft:fias:status
    ```

4. Список доступных для установки и обновления версий ФИАС

    ```bash
    bin/console liquetsoft:fias:versions
    ```

5. Загрузка и распаковка архива с полной версией ФИАС

    ```bash
    bin/console liquetsoft:fias:download /path/to/download latest --extract
    ```

6. Установка ФИАС из указанного каталога

    ```bash
    bin/console liquetsoft:fias:install_from_folder /path/to/extracted/fias
    ```

7. Обновление ФИАС из указанного каталога

    ```bash
    bin/console liquetsoft:fias:update_from_folder /path/to/extracted/fias
    ```

8. Принудительная установка номера текущей версии ФИАС

    ```bash
    bin/console liquetsoft:fias:version_set 20160101
    ```



Производительность
------------------

Есть несколько возможностей ускорить импорт, используя настройки бандла:

1. убрать неиспользуемые сущности; к примеру, если информация о парковочных местах не требуется, то можно отключить соответствие для CARPLACES

    ```yaml
    # config/packages/liquetsoft_fias.yaml
    liquetsoft_fias:
        entity_bindings:
            # CARPLACES: App\Entity\Carplaces
    ```
2. поскольку в формате ГАР все данные разделены по папкам регионов, то можно исключить обработку файлов для неиспользуемых регионов

    ```yaml
    # config/packages/liquetsoft_fias.yaml
    liquetsoft_fias:
        files_filter:
            - "#^.+/extracted/30/AS_.+$#" # разрешает все данные для региона
            - "#^.+/extracted/AS_.+$#"    # разрешает общие словари
            # все остальные файлы будут проигнорированы
