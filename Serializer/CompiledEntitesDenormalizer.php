<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer;

use Liquetsoft\Fias\Component\Serializer\FiasSerializerFormat;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddrObj;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddrObjDivision;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddrObjTypes;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AdmHierarchy;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Apartments;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ApartmentTypes;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Carplaces;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ChangeHistory;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\FiasVersion;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Houses;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\HouseTypes;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\MunHierarchy;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocs;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocsKinds;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocsTypes;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ObjectLevels;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\OperationTypes;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Param;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ParamTypes;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ReestrObjects;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Rooms;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\RoomTypes;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Steads;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Скомпилированный класс для денормализации сущностей ФИАС в модели.
 */
final class CompiledEntitesDenormalizer implements DenormalizerAwareInterface, DenormalizerInterface
{
    use DenormalizerAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress MissingParamType
     */
    #[\Override]
    public function supportsDenormalization(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = [],
    ): bool {
        return empty($context['fias_compiled_data_set'])
            && FiasSerializerFormat::XML->isEqual($format)
            && (
                is_subclass_of($type, Apartments::class)
                || is_subclass_of($type, AddrObjDivision::class)
                || is_subclass_of($type, NormativeDocsTypes::class)
                || is_subclass_of($type, RoomTypes::class)
                || is_subclass_of($type, ObjectLevels::class)
                || is_subclass_of($type, NormativeDocsKinds::class)
                || is_subclass_of($type, Rooms::class)
                || is_subclass_of($type, ApartmentTypes::class)
                || is_subclass_of($type, AddrObjTypes::class)
                || is_subclass_of($type, Steads::class)
                || is_subclass_of($type, NormativeDocs::class)
                || is_subclass_of($type, OperationTypes::class)
                || is_subclass_of($type, Houses::class)
                || is_subclass_of($type, AdmHierarchy::class)
                || is_subclass_of($type, Carplaces::class)
                || is_subclass_of($type, ChangeHistory::class)
                || is_subclass_of($type, AddrObj::class)
                || is_subclass_of($type, ParamTypes::class)
                || is_subclass_of($type, Param::class)
                || is_subclass_of($type, ReestrObjects::class)
                || is_subclass_of($type, HouseTypes::class)
                || is_subclass_of($type, MunHierarchy::class)
                || is_subclass_of($type, FiasVersion::class)
            )
        ;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress InvalidStringClass
     */
    #[\Override]
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (!\is_array($data)) {
            throw new InvalidArgumentException('Bad data parameter. Array instance is required');
        }

        unset($data['#']);

        $type = trim($type, " \t\n\r\0\x0B/");

        $entity = $context[AbstractNormalizer::OBJECT_TO_POPULATE] ?? new $type();

        if ($entity instanceof Apartments) {
            $data = $this->setDataToApartmentsEntity($entity, $data);
        } elseif ($entity instanceof AddrObjDivision) {
            $data = $this->setDataToAddrObjDivisionEntity($entity, $data);
        } elseif ($entity instanceof NormativeDocsTypes) {
            $data = $this->setDataToNormativeDocsTypesEntity($entity, $data);
        } elseif ($entity instanceof RoomTypes) {
            $data = $this->setDataToRoomTypesEntity($entity, $data);
        } elseif ($entity instanceof ObjectLevels) {
            $data = $this->setDataToObjectLevelsEntity($entity, $data);
        } elseif ($entity instanceof NormativeDocsKinds) {
            $data = $this->setDataToNormativeDocsKindsEntity($entity, $data);
        } elseif ($entity instanceof Rooms) {
            $data = $this->setDataToRoomsEntity($entity, $data);
        } elseif ($entity instanceof ApartmentTypes) {
            $data = $this->setDataToApartmentTypesEntity($entity, $data);
        } elseif ($entity instanceof AddrObjTypes) {
            $data = $this->setDataToAddrObjTypesEntity($entity, $data);
        } elseif ($entity instanceof Steads) {
            $data = $this->setDataToSteadsEntity($entity, $data);
        } elseif ($entity instanceof NormativeDocs) {
            $data = $this->setDataToNormativeDocsEntity($entity, $data);
        } elseif ($entity instanceof OperationTypes) {
            $data = $this->setDataToOperationTypesEntity($entity, $data);
        } elseif ($entity instanceof Houses) {
            $data = $this->setDataToHousesEntity($entity, $data);
        } elseif ($entity instanceof AdmHierarchy) {
            $data = $this->setDataToAdmHierarchyEntity($entity, $data);
        } elseif ($entity instanceof Carplaces) {
            $data = $this->setDataToCarplacesEntity($entity, $data);
        } elseif ($entity instanceof ChangeHistory) {
            $data = $this->setDataToChangeHistoryEntity($entity, $data);
        } elseif ($entity instanceof AddrObj) {
            $data = $this->setDataToAddrObjEntity($entity, $data);
        } elseif ($entity instanceof ParamTypes) {
            $data = $this->setDataToParamTypesEntity($entity, $data);
        } elseif ($entity instanceof Param) {
            $data = $this->setDataToParamEntity($entity, $data);
        } elseif ($entity instanceof ReestrObjects) {
            $data = $this->setDataToReestrObjectsEntity($entity, $data);
        } elseif ($entity instanceof HouseTypes) {
            $data = $this->setDataToHouseTypesEntity($entity, $data);
        } elseif ($entity instanceof MunHierarchy) {
            $data = $this->setDataToMunHierarchyEntity($entity, $data);
        } elseif ($entity instanceof FiasVersion) {
            $data = $this->setDataToFiasVersionEntity($entity, $data);
        } else {
            throw new InvalidArgumentException("Can't find data extractor for '{$type}' type");
        }

        if (!empty($data)) {
            $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $entity;
            $context['fias_compiled_data_set'] = true;
            $entity = $this->denormalizer->denormalize($data, $type, $format, $context);
        }

        return $entity;
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string, bool|null>
     */
    #[\Override]
    public function getSupportedTypes(?string $format): array
    {
        return !FiasSerializerFormat::XML->isEqual($format) ? [] : [
            Apartments::class => false,
            AddrObjDivision::class => false,
            NormativeDocsTypes::class => false,
            RoomTypes::class => false,
            ObjectLevels::class => false,
            NormativeDocsKinds::class => false,
            Rooms::class => false,
            ApartmentTypes::class => false,
            AddrObjTypes::class => false,
            Steads::class => false,
            NormativeDocs::class => false,
            OperationTypes::class => false,
            Houses::class => false,
            AdmHierarchy::class => false,
            Carplaces::class => false,
            ChangeHistory::class => false,
            AddrObj::class => false,
            ParamTypes::class => false,
            Param::class => false,
            ReestrObjects::class => false,
            HouseTypes::class => false,
            MunHierarchy::class => false,
            FiasVersion::class => false,
        ];
    }

    /**
     * Наполняет сущность 'Apartments' данными и возвращает те, которые не были использованы.
     */
    private function setDataToApartmentsEntity(Apartments $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@OBJECTID', $data)) {
            $entity->setObjectid((int) $data['@OBJECTID']);
            unset($data['@OBJECTID']);
        }
        if (\array_key_exists('@OBJECTGUID', $data)) {
            $entity->setObjectguid(Uuid::fromString((string) $data['@OBJECTGUID']));
            unset($data['@OBJECTGUID']);
        }
        if (\array_key_exists('@CHANGEID', $data)) {
            $entity->setChangeid((int) $data['@CHANGEID']);
            unset($data['@CHANGEID']);
        }
        if (\array_key_exists('@NUMBER', $data)) {
            $entity->setNumber((string) $data['@NUMBER']);
            unset($data['@NUMBER']);
        }
        if (\array_key_exists('@APARTTYPE', $data)) {
            $entity->setAparttype((int) $data['@APARTTYPE']);
            unset($data['@APARTTYPE']);
        }
        if (\array_key_exists('@OPERTYPEID', $data)) {
            $entity->setOpertypeid((int) $data['@OPERTYPEID']);
            unset($data['@OPERTYPEID']);
        }
        if (\array_key_exists('@PREVID', $data)) {
            $entity->setPrevid($data['@PREVID'] === null || $data['@PREVID'] === '' ? null : (int) $data['@PREVID']);
            unset($data['@PREVID']);
        }
        if (\array_key_exists('@NEXTID', $data)) {
            $entity->setNextid($data['@NEXTID'] === null || $data['@NEXTID'] === '' ? null : (int) $data['@NEXTID']);
            unset($data['@NEXTID']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTUAL', $data)) {
            $entity->setIsactual((int) $data['@ISACTUAL']);
            unset($data['@ISACTUAL']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((int) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'AddrObjDivision' данными и возвращает те, которые не были использованы.
     */
    private function setDataToAddrObjDivisionEntity(AddrObjDivision $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@PARENTID', $data)) {
            $entity->setParentid((int) $data['@PARENTID']);
            unset($data['@PARENTID']);
        }
        if (\array_key_exists('@CHILDID', $data)) {
            $entity->setChildid((int) $data['@CHILDID']);
            unset($data['@CHILDID']);
        }
        if (\array_key_exists('@CHANGEID', $data)) {
            $entity->setChangeid((int) $data['@CHANGEID']);
            unset($data['@CHANGEID']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'NormativeDocsTypes' данными и возвращает те, которые не были использованы.
     */
    private function setDataToNormativeDocsTypesEntity(NormativeDocsTypes $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@NAME', $data)) {
            $entity->setName((string) $data['@NAME']);
            unset($data['@NAME']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'RoomTypes' данными и возвращает те, которые не были использованы.
     */
    private function setDataToRoomTypesEntity(RoomTypes $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@NAME', $data)) {
            $entity->setName((string) $data['@NAME']);
            unset($data['@NAME']);
        }
        if (\array_key_exists('@SHORTNAME', $data)) {
            $entity->setShortname($data['@SHORTNAME'] === null || $data['@SHORTNAME'] === '' ? null : (string) $data['@SHORTNAME']);
            unset($data['@SHORTNAME']);
        }
        if (\array_key_exists('@DESC', $data)) {
            $entity->setDesc($data['@DESC'] === null || $data['@DESC'] === '' ? null : (string) $data['@DESC']);
            unset($data['@DESC']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((string) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'ObjectLevels' данными и возвращает те, которые не были использованы.
     */
    private function setDataToObjectLevelsEntity(ObjectLevels $entity, array $data): array
    {
        if (\array_key_exists('@LEVEL', $data)) {
            $entity->setLevel((int) $data['@LEVEL']);
            unset($data['@LEVEL']);
        }
        if (\array_key_exists('@NAME', $data)) {
            $entity->setName((string) $data['@NAME']);
            unset($data['@NAME']);
        }
        if (\array_key_exists('@SHORTNAME', $data)) {
            $entity->setShortname($data['@SHORTNAME'] === null || $data['@SHORTNAME'] === '' ? null : (string) $data['@SHORTNAME']);
            unset($data['@SHORTNAME']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((string) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'NormativeDocsKinds' данными и возвращает те, которые не были использованы.
     */
    private function setDataToNormativeDocsKindsEntity(NormativeDocsKinds $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@NAME', $data)) {
            $entity->setName((string) $data['@NAME']);
            unset($data['@NAME']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'Rooms' данными и возвращает те, которые не были использованы.
     */
    private function setDataToRoomsEntity(Rooms $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@OBJECTID', $data)) {
            $entity->setObjectid((int) $data['@OBJECTID']);
            unset($data['@OBJECTID']);
        }
        if (\array_key_exists('@OBJECTGUID', $data)) {
            $entity->setObjectguid(Uuid::fromString((string) $data['@OBJECTGUID']));
            unset($data['@OBJECTGUID']);
        }
        if (\array_key_exists('@CHANGEID', $data)) {
            $entity->setChangeid((int) $data['@CHANGEID']);
            unset($data['@CHANGEID']);
        }
        if (\array_key_exists('@NUMBER', $data)) {
            $entity->setNumber((string) $data['@NUMBER']);
            unset($data['@NUMBER']);
        }
        if (\array_key_exists('@ROOMTYPE', $data)) {
            $entity->setRoomtype((int) $data['@ROOMTYPE']);
            unset($data['@ROOMTYPE']);
        }
        if (\array_key_exists('@OPERTYPEID', $data)) {
            $entity->setOpertypeid((int) $data['@OPERTYPEID']);
            unset($data['@OPERTYPEID']);
        }
        if (\array_key_exists('@PREVID', $data)) {
            $entity->setPrevid($data['@PREVID'] === null || $data['@PREVID'] === '' ? null : (int) $data['@PREVID']);
            unset($data['@PREVID']);
        }
        if (\array_key_exists('@NEXTID', $data)) {
            $entity->setNextid($data['@NEXTID'] === null || $data['@NEXTID'] === '' ? null : (int) $data['@NEXTID']);
            unset($data['@NEXTID']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTUAL', $data)) {
            $entity->setIsactual((int) $data['@ISACTUAL']);
            unset($data['@ISACTUAL']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((int) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'ApartmentTypes' данными и возвращает те, которые не были использованы.
     */
    private function setDataToApartmentTypesEntity(ApartmentTypes $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@NAME', $data)) {
            $entity->setName((string) $data['@NAME']);
            unset($data['@NAME']);
        }
        if (\array_key_exists('@SHORTNAME', $data)) {
            $entity->setShortname($data['@SHORTNAME'] === null || $data['@SHORTNAME'] === '' ? null : (string) $data['@SHORTNAME']);
            unset($data['@SHORTNAME']);
        }
        if (\array_key_exists('@DESC', $data)) {
            $entity->setDesc($data['@DESC'] === null || $data['@DESC'] === '' ? null : (string) $data['@DESC']);
            unset($data['@DESC']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((string) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'AddrObjTypes' данными и возвращает те, которые не были использованы.
     */
    private function setDataToAddrObjTypesEntity(AddrObjTypes $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@LEVEL', $data)) {
            $entity->setLevel((int) $data['@LEVEL']);
            unset($data['@LEVEL']);
        }
        if (\array_key_exists('@SHORTNAME', $data)) {
            $entity->setShortname((string) $data['@SHORTNAME']);
            unset($data['@SHORTNAME']);
        }
        if (\array_key_exists('@NAME', $data)) {
            $entity->setName((string) $data['@NAME']);
            unset($data['@NAME']);
        }
        if (\array_key_exists('@DESC', $data)) {
            $entity->setDesc($data['@DESC'] === null || $data['@DESC'] === '' ? null : (string) $data['@DESC']);
            unset($data['@DESC']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((string) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'Steads' данными и возвращает те, которые не были использованы.
     */
    private function setDataToSteadsEntity(Steads $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@OBJECTID', $data)) {
            $entity->setObjectid((int) $data['@OBJECTID']);
            unset($data['@OBJECTID']);
        }
        if (\array_key_exists('@OBJECTGUID', $data)) {
            $entity->setObjectguid(Uuid::fromString((string) $data['@OBJECTGUID']));
            unset($data['@OBJECTGUID']);
        }
        if (\array_key_exists('@CHANGEID', $data)) {
            $entity->setChangeid((int) $data['@CHANGEID']);
            unset($data['@CHANGEID']);
        }
        if (\array_key_exists('@NUMBER', $data)) {
            $entity->setNumber((string) $data['@NUMBER']);
            unset($data['@NUMBER']);
        }
        if (\array_key_exists('@OPERTYPEID', $data)) {
            $entity->setOpertypeid((string) $data['@OPERTYPEID']);
            unset($data['@OPERTYPEID']);
        }
        if (\array_key_exists('@PREVID', $data)) {
            $entity->setPrevid($data['@PREVID'] === null || $data['@PREVID'] === '' ? null : (int) $data['@PREVID']);
            unset($data['@PREVID']);
        }
        if (\array_key_exists('@NEXTID', $data)) {
            $entity->setNextid($data['@NEXTID'] === null || $data['@NEXTID'] === '' ? null : (int) $data['@NEXTID']);
            unset($data['@NEXTID']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTUAL', $data)) {
            $entity->setIsactual((int) $data['@ISACTUAL']);
            unset($data['@ISACTUAL']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((int) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'NormativeDocs' данными и возвращает те, которые не были использованы.
     */
    private function setDataToNormativeDocsEntity(NormativeDocs $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@NAME', $data)) {
            $entity->setName((string) $data['@NAME']);
            unset($data['@NAME']);
        }
        if (\array_key_exists('@DATE', $data)) {
            $entity->setDate(new \DateTimeImmutable((string) $data['@DATE']));
            unset($data['@DATE']);
        }
        if (\array_key_exists('@NUMBER', $data)) {
            $entity->setNumber((string) $data['@NUMBER']);
            unset($data['@NUMBER']);
        }
        if (\array_key_exists('@TYPE', $data)) {
            $entity->setType((int) $data['@TYPE']);
            unset($data['@TYPE']);
        }
        if (\array_key_exists('@KIND', $data)) {
            $entity->setKind((int) $data['@KIND']);
            unset($data['@KIND']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@ORGNAME', $data)) {
            $entity->setOrgname($data['@ORGNAME'] === null || $data['@ORGNAME'] === '' ? null : (string) $data['@ORGNAME']);
            unset($data['@ORGNAME']);
        }
        if (\array_key_exists('@REGNUM', $data)) {
            $entity->setRegnum($data['@REGNUM'] === null || $data['@REGNUM'] === '' ? null : (string) $data['@REGNUM']);
            unset($data['@REGNUM']);
        }
        if (\array_key_exists('@REGDATE', $data)) {
            $entity->setRegdate($data['@REGDATE'] === null || $data['@REGDATE'] === '' ? null : new \DateTimeImmutable((string) $data['@REGDATE']));
            unset($data['@REGDATE']);
        }
        if (\array_key_exists('@ACCDATE', $data)) {
            $entity->setAccdate($data['@ACCDATE'] === null || $data['@ACCDATE'] === '' ? null : new \DateTimeImmutable((string) $data['@ACCDATE']));
            unset($data['@ACCDATE']);
        }
        if (\array_key_exists('@COMMENT', $data)) {
            $entity->setComment($data['@COMMENT'] === null || $data['@COMMENT'] === '' ? null : (string) $data['@COMMENT']);
            unset($data['@COMMENT']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'OperationTypes' данными и возвращает те, которые не были использованы.
     */
    private function setDataToOperationTypesEntity(OperationTypes $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@NAME', $data)) {
            $entity->setName((string) $data['@NAME']);
            unset($data['@NAME']);
        }
        if (\array_key_exists('@SHORTNAME', $data)) {
            $entity->setShortname($data['@SHORTNAME'] === null || $data['@SHORTNAME'] === '' ? null : (string) $data['@SHORTNAME']);
            unset($data['@SHORTNAME']);
        }
        if (\array_key_exists('@DESC', $data)) {
            $entity->setDesc($data['@DESC'] === null || $data['@DESC'] === '' ? null : (string) $data['@DESC']);
            unset($data['@DESC']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((string) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'Houses' данными и возвращает те, которые не были использованы.
     */
    private function setDataToHousesEntity(Houses $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@OBJECTID', $data)) {
            $entity->setObjectid((int) $data['@OBJECTID']);
            unset($data['@OBJECTID']);
        }
        if (\array_key_exists('@OBJECTGUID', $data)) {
            $entity->setObjectguid(Uuid::fromString((string) $data['@OBJECTGUID']));
            unset($data['@OBJECTGUID']);
        }
        if (\array_key_exists('@CHANGEID', $data)) {
            $entity->setChangeid((int) $data['@CHANGEID']);
            unset($data['@CHANGEID']);
        }
        if (\array_key_exists('@HOUSENUM', $data)) {
            $entity->setHousenum($data['@HOUSENUM'] === null || $data['@HOUSENUM'] === '' ? null : (string) $data['@HOUSENUM']);
            unset($data['@HOUSENUM']);
        }
        if (\array_key_exists('@ADDNUM1', $data)) {
            $entity->setAddnum1($data['@ADDNUM1'] === null || $data['@ADDNUM1'] === '' ? null : (string) $data['@ADDNUM1']);
            unset($data['@ADDNUM1']);
        }
        if (\array_key_exists('@ADDNUM2', $data)) {
            $entity->setAddnum2($data['@ADDNUM2'] === null || $data['@ADDNUM2'] === '' ? null : (string) $data['@ADDNUM2']);
            unset($data['@ADDNUM2']);
        }
        if (\array_key_exists('@HOUSETYPE', $data)) {
            $entity->setHousetype($data['@HOUSETYPE'] === null || $data['@HOUSETYPE'] === '' ? null : (int) $data['@HOUSETYPE']);
            unset($data['@HOUSETYPE']);
        }
        if (\array_key_exists('@ADDTYPE1', $data)) {
            $entity->setAddtype1($data['@ADDTYPE1'] === null || $data['@ADDTYPE1'] === '' ? null : (int) $data['@ADDTYPE1']);
            unset($data['@ADDTYPE1']);
        }
        if (\array_key_exists('@ADDTYPE2', $data)) {
            $entity->setAddtype2($data['@ADDTYPE2'] === null || $data['@ADDTYPE2'] === '' ? null : (int) $data['@ADDTYPE2']);
            unset($data['@ADDTYPE2']);
        }
        if (\array_key_exists('@OPERTYPEID', $data)) {
            $entity->setOpertypeid((int) $data['@OPERTYPEID']);
            unset($data['@OPERTYPEID']);
        }
        if (\array_key_exists('@PREVID', $data)) {
            $entity->setPrevid($data['@PREVID'] === null || $data['@PREVID'] === '' ? null : (int) $data['@PREVID']);
            unset($data['@PREVID']);
        }
        if (\array_key_exists('@NEXTID', $data)) {
            $entity->setNextid($data['@NEXTID'] === null || $data['@NEXTID'] === '' ? null : (int) $data['@NEXTID']);
            unset($data['@NEXTID']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTUAL', $data)) {
            $entity->setIsactual((int) $data['@ISACTUAL']);
            unset($data['@ISACTUAL']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((int) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'AdmHierarchy' данными и возвращает те, которые не были использованы.
     */
    private function setDataToAdmHierarchyEntity(AdmHierarchy $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@OBJECTID', $data)) {
            $entity->setObjectid((int) $data['@OBJECTID']);
            unset($data['@OBJECTID']);
        }
        if (\array_key_exists('@PARENTOBJID', $data)) {
            $entity->setParentobjid($data['@PARENTOBJID'] === null || $data['@PARENTOBJID'] === '' ? null : (int) $data['@PARENTOBJID']);
            unset($data['@PARENTOBJID']);
        }
        if (\array_key_exists('@CHANGEID', $data)) {
            $entity->setChangeid((int) $data['@CHANGEID']);
            unset($data['@CHANGEID']);
        }
        if (\array_key_exists('@REGIONCODE', $data)) {
            $entity->setRegioncode($data['@REGIONCODE'] === null || $data['@REGIONCODE'] === '' ? null : (string) $data['@REGIONCODE']);
            unset($data['@REGIONCODE']);
        }
        if (\array_key_exists('@AREACODE', $data)) {
            $entity->setAreacode($data['@AREACODE'] === null || $data['@AREACODE'] === '' ? null : (string) $data['@AREACODE']);
            unset($data['@AREACODE']);
        }
        if (\array_key_exists('@CITYCODE', $data)) {
            $entity->setCitycode($data['@CITYCODE'] === null || $data['@CITYCODE'] === '' ? null : (string) $data['@CITYCODE']);
            unset($data['@CITYCODE']);
        }
        if (\array_key_exists('@PLACECODE', $data)) {
            $entity->setPlacecode($data['@PLACECODE'] === null || $data['@PLACECODE'] === '' ? null : (string) $data['@PLACECODE']);
            unset($data['@PLACECODE']);
        }
        if (\array_key_exists('@PLANCODE', $data)) {
            $entity->setPlancode($data['@PLANCODE'] === null || $data['@PLANCODE'] === '' ? null : (string) $data['@PLANCODE']);
            unset($data['@PLANCODE']);
        }
        if (\array_key_exists('@STREETCODE', $data)) {
            $entity->setStreetcode($data['@STREETCODE'] === null || $data['@STREETCODE'] === '' ? null : (string) $data['@STREETCODE']);
            unset($data['@STREETCODE']);
        }
        if (\array_key_exists('@PREVID', $data)) {
            $entity->setPrevid($data['@PREVID'] === null || $data['@PREVID'] === '' ? null : (int) $data['@PREVID']);
            unset($data['@PREVID']);
        }
        if (\array_key_exists('@NEXTID', $data)) {
            $entity->setNextid($data['@NEXTID'] === null || $data['@NEXTID'] === '' ? null : (int) $data['@NEXTID']);
            unset($data['@NEXTID']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((int) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }
        if (\array_key_exists('@PATH', $data)) {
            $entity->setPath((string) $data['@PATH']);
            unset($data['@PATH']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'Carplaces' данными и возвращает те, которые не были использованы.
     */
    private function setDataToCarplacesEntity(Carplaces $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@OBJECTID', $data)) {
            $entity->setObjectid((int) $data['@OBJECTID']);
            unset($data['@OBJECTID']);
        }
        if (\array_key_exists('@OBJECTGUID', $data)) {
            $entity->setObjectguid(Uuid::fromString((string) $data['@OBJECTGUID']));
            unset($data['@OBJECTGUID']);
        }
        if (\array_key_exists('@CHANGEID', $data)) {
            $entity->setChangeid((int) $data['@CHANGEID']);
            unset($data['@CHANGEID']);
        }
        if (\array_key_exists('@NUMBER', $data)) {
            $entity->setNumber((string) $data['@NUMBER']);
            unset($data['@NUMBER']);
        }
        if (\array_key_exists('@OPERTYPEID', $data)) {
            $entity->setOpertypeid((int) $data['@OPERTYPEID']);
            unset($data['@OPERTYPEID']);
        }
        if (\array_key_exists('@PREVID', $data)) {
            $entity->setPrevid($data['@PREVID'] === null || $data['@PREVID'] === '' ? null : (int) $data['@PREVID']);
            unset($data['@PREVID']);
        }
        if (\array_key_exists('@NEXTID', $data)) {
            $entity->setNextid($data['@NEXTID'] === null || $data['@NEXTID'] === '' ? null : (int) $data['@NEXTID']);
            unset($data['@NEXTID']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTUAL', $data)) {
            $entity->setIsactual((int) $data['@ISACTUAL']);
            unset($data['@ISACTUAL']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((int) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'ChangeHistory' данными и возвращает те, которые не были использованы.
     */
    private function setDataToChangeHistoryEntity(ChangeHistory $entity, array $data): array
    {
        if (\array_key_exists('@CHANGEID', $data)) {
            $entity->setChangeid((int) $data['@CHANGEID']);
            unset($data['@CHANGEID']);
        }
        if (\array_key_exists('@OBJECTID', $data)) {
            $entity->setObjectid((int) $data['@OBJECTID']);
            unset($data['@OBJECTID']);
        }
        if (\array_key_exists('@ADROBJECTID', $data)) {
            $entity->setAdrobjectid(Uuid::fromString((string) $data['@ADROBJECTID']));
            unset($data['@ADROBJECTID']);
        }
        if (\array_key_exists('@OPERTYPEID', $data)) {
            $entity->setOpertypeid((int) $data['@OPERTYPEID']);
            unset($data['@OPERTYPEID']);
        }
        if (\array_key_exists('@NDOCID', $data)) {
            $entity->setNdocid($data['@NDOCID'] === null || $data['@NDOCID'] === '' ? null : (int) $data['@NDOCID']);
            unset($data['@NDOCID']);
        }
        if (\array_key_exists('@CHANGEDATE', $data)) {
            $entity->setChangedate(new \DateTimeImmutable((string) $data['@CHANGEDATE']));
            unset($data['@CHANGEDATE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'AddrObj' данными и возвращает те, которые не были использованы.
     */
    private function setDataToAddrObjEntity(AddrObj $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@OBJECTID', $data)) {
            $entity->setObjectid((int) $data['@OBJECTID']);
            unset($data['@OBJECTID']);
        }
        if (\array_key_exists('@OBJECTGUID', $data)) {
            $entity->setObjectguid(Uuid::fromString((string) $data['@OBJECTGUID']));
            unset($data['@OBJECTGUID']);
        }
        if (\array_key_exists('@CHANGEID', $data)) {
            $entity->setChangeid((int) $data['@CHANGEID']);
            unset($data['@CHANGEID']);
        }
        if (\array_key_exists('@NAME', $data)) {
            $entity->setName((string) $data['@NAME']);
            unset($data['@NAME']);
        }
        if (\array_key_exists('@TYPENAME', $data)) {
            $entity->setTypename((string) $data['@TYPENAME']);
            unset($data['@TYPENAME']);
        }
        if (\array_key_exists('@LEVEL', $data)) {
            $entity->setLevel((string) $data['@LEVEL']);
            unset($data['@LEVEL']);
        }
        if (\array_key_exists('@OPERTYPEID', $data)) {
            $entity->setOpertypeid((int) $data['@OPERTYPEID']);
            unset($data['@OPERTYPEID']);
        }
        if (\array_key_exists('@PREVID', $data)) {
            $entity->setPrevid($data['@PREVID'] === null || $data['@PREVID'] === '' ? null : (int) $data['@PREVID']);
            unset($data['@PREVID']);
        }
        if (\array_key_exists('@NEXTID', $data)) {
            $entity->setNextid($data['@NEXTID'] === null || $data['@NEXTID'] === '' ? null : (int) $data['@NEXTID']);
            unset($data['@NEXTID']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTUAL', $data)) {
            $entity->setIsactual((int) $data['@ISACTUAL']);
            unset($data['@ISACTUAL']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((int) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'ParamTypes' данными и возвращает те, которые не были использованы.
     */
    private function setDataToParamTypesEntity(ParamTypes $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@NAME', $data)) {
            $entity->setName((string) $data['@NAME']);
            unset($data['@NAME']);
        }
        if (\array_key_exists('@CODE', $data)) {
            $entity->setCode((string) $data['@CODE']);
            unset($data['@CODE']);
        }
        if (\array_key_exists('@DESC', $data)) {
            $entity->setDesc($data['@DESC'] === null || $data['@DESC'] === '' ? null : (string) $data['@DESC']);
            unset($data['@DESC']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((string) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'Param' данными и возвращает те, которые не были использованы.
     */
    private function setDataToParamEntity(Param $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@OBJECTID', $data)) {
            $entity->setObjectid((int) $data['@OBJECTID']);
            unset($data['@OBJECTID']);
        }
        if (\array_key_exists('@CHANGEID', $data)) {
            $entity->setChangeid($data['@CHANGEID'] === null || $data['@CHANGEID'] === '' ? null : (int) $data['@CHANGEID']);
            unset($data['@CHANGEID']);
        }
        if (\array_key_exists('@CHANGEIDEND', $data)) {
            $entity->setChangeidend((int) $data['@CHANGEIDEND']);
            unset($data['@CHANGEIDEND']);
        }
        if (\array_key_exists('@TYPEID', $data)) {
            $entity->setTypeid((int) $data['@TYPEID']);
            unset($data['@TYPEID']);
        }
        if (\array_key_exists('@VALUE', $data)) {
            $entity->setValue((string) $data['@VALUE']);
            unset($data['@VALUE']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'ReestrObjects' данными и возвращает те, которые не были использованы.
     */
    private function setDataToReestrObjectsEntity(ReestrObjects $entity, array $data): array
    {
        if (\array_key_exists('@OBJECTID', $data)) {
            $entity->setObjectid((int) $data['@OBJECTID']);
            unset($data['@OBJECTID']);
        }
        if (\array_key_exists('@CREATEDATE', $data)) {
            $entity->setCreatedate(new \DateTimeImmutable((string) $data['@CREATEDATE']));
            unset($data['@CREATEDATE']);
        }
        if (\array_key_exists('@CHANGEID', $data)) {
            $entity->setChangeid((int) $data['@CHANGEID']);
            unset($data['@CHANGEID']);
        }
        if (\array_key_exists('@LEVELID', $data)) {
            $entity->setLevelid((int) $data['@LEVELID']);
            unset($data['@LEVELID']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@OBJECTGUID', $data)) {
            $entity->setObjectguid(Uuid::fromString((string) $data['@OBJECTGUID']));
            unset($data['@OBJECTGUID']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((int) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'HouseTypes' данными и возвращает те, которые не были использованы.
     */
    private function setDataToHouseTypesEntity(HouseTypes $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@NAME', $data)) {
            $entity->setName((string) $data['@NAME']);
            unset($data['@NAME']);
        }
        if (\array_key_exists('@SHORTNAME', $data)) {
            $entity->setShortname($data['@SHORTNAME'] === null || $data['@SHORTNAME'] === '' ? null : (string) $data['@SHORTNAME']);
            unset($data['@SHORTNAME']);
        }
        if (\array_key_exists('@DESC', $data)) {
            $entity->setDesc($data['@DESC'] === null || $data['@DESC'] === '' ? null : (string) $data['@DESC']);
            unset($data['@DESC']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((string) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'MunHierarchy' данными и возвращает те, которые не были использованы.
     */
    private function setDataToMunHierarchyEntity(MunHierarchy $entity, array $data): array
    {
        if (\array_key_exists('@ID', $data)) {
            $entity->setId((int) $data['@ID']);
            unset($data['@ID']);
        }
        if (\array_key_exists('@OBJECTID', $data)) {
            $entity->setObjectid((int) $data['@OBJECTID']);
            unset($data['@OBJECTID']);
        }
        if (\array_key_exists('@PARENTOBJID', $data)) {
            $entity->setParentobjid($data['@PARENTOBJID'] === null || $data['@PARENTOBJID'] === '' ? null : (int) $data['@PARENTOBJID']);
            unset($data['@PARENTOBJID']);
        }
        if (\array_key_exists('@CHANGEID', $data)) {
            $entity->setChangeid((int) $data['@CHANGEID']);
            unset($data['@CHANGEID']);
        }
        if (\array_key_exists('@OKTMO', $data)) {
            $entity->setOktmo($data['@OKTMO'] === null || $data['@OKTMO'] === '' ? null : (string) $data['@OKTMO']);
            unset($data['@OKTMO']);
        }
        if (\array_key_exists('@PREVID', $data)) {
            $entity->setPrevid($data['@PREVID'] === null || $data['@PREVID'] === '' ? null : (int) $data['@PREVID']);
            unset($data['@PREVID']);
        }
        if (\array_key_exists('@NEXTID', $data)) {
            $entity->setNextid($data['@NEXTID'] === null || $data['@NEXTID'] === '' ? null : (int) $data['@NEXTID']);
            unset($data['@NEXTID']);
        }
        if (\array_key_exists('@UPDATEDATE', $data)) {
            $entity->setUpdatedate(new \DateTimeImmutable((string) $data['@UPDATEDATE']));
            unset($data['@UPDATEDATE']);
        }
        if (\array_key_exists('@STARTDATE', $data)) {
            $entity->setStartdate(new \DateTimeImmutable((string) $data['@STARTDATE']));
            unset($data['@STARTDATE']);
        }
        if (\array_key_exists('@ENDDATE', $data)) {
            $entity->setEnddate(new \DateTimeImmutable((string) $data['@ENDDATE']));
            unset($data['@ENDDATE']);
        }
        if (\array_key_exists('@ISACTIVE', $data)) {
            $entity->setIsactive((int) $data['@ISACTIVE']);
            unset($data['@ISACTIVE']);
        }
        if (\array_key_exists('@PATH', $data)) {
            $entity->setPath((string) $data['@PATH']);
            unset($data['@PATH']);
        }

        return $data;
    }

    /**
     * Наполняет сущность 'FiasVersion' данными и возвращает те, которые не были использованы.
     */
    private function setDataToFiasVersionEntity(FiasVersion $entity, array $data): array
    {
        if (\array_key_exists('@VERSION', $data)) {
            $entity->setVersion((int) $data['@VERSION']);
            unset($data['@VERSION']);
        }
        if (\array_key_exists('@FULLURL', $data)) {
            $entity->setFullurl((string) $data['@FULLURL']);
            unset($data['@FULLURL']);
        }
        if (\array_key_exists('@DELTAURL', $data)) {
            $entity->setDeltaurl((string) $data['@DELTAURL']);
            unset($data['@DELTAURL']);
        }
        if (\array_key_exists('@CREATED', $data)) {
            $entity->setCreated(new \DateTimeImmutable((string) $data['@CREATED']));
            unset($data['@CREATED']);
        }

        return $data;
    }
}
