<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use Liquetsoft\Fias\Component\EntityDescriptor\BaseEntityDescriptor;
use Liquetsoft\Fias\Component\EntityField\BaseEntityField;
use Liquetsoft\Fias\Component\EntityManager\BaseEntityManager;
use Liquetsoft\Fias\Component\EntityRegistry\ArrayEntityRegistry;
use Liquetsoft\Fias\Component\Pipeline\Pipe\ArrayPipe;
use Liquetsoft\Fias\Component\Pipeline\Pipe\Pipe;
use Liquetsoft\Fias\Component\Pipeline\State\ArrayState;
use Liquetsoft\Fias\Component\Pipeline\State\StateParameter;
use Liquetsoft\Fias\Component\Pipeline\Task\CleanupTask;
use Liquetsoft\Fias\Component\Pipeline\Task\DataDeleteTask;
use Liquetsoft\Fias\Component\Pipeline\Task\DataUpsertTask;
use Liquetsoft\Fias\Component\Pipeline\Task\SelectFilesToProceedTask;
use Liquetsoft\Fias\Component\Pipeline\Task\UnpackTask;
use Liquetsoft\Fias\Component\Pipeline\Task\VersionSetTask;
use Liquetsoft\Fias\Component\Unpacker\ZipUnpacker;
use Liquetsoft\Fias\Component\XmlReader\BaseXmlReader;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer\FiasSerializer;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage\BulkInsertDoctrineStorage;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\DoctrineTestCase;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\PipelineTestMockEntity;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\VersionManagerTestMockEntity;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\VersionManager\DoctrineVersionManager;
use Symfony\Component\Uid\Uuid;

/**
 * Тест для объекта папйлайна для обновления базы данных.
 *
 * @internal
 */
final class UpdatePipelineTest extends DoctrineTestCase
{
    /**
     * Тест для проверки пайплайна с обновлением ФИАС до новой версии.
     */
    public function testUpdate(): void
    {
        $testDir = $this->getPathToTestDir();
        $testArchive = "{$testDir}/update.zip";

        copy(__DIR__ . '/_fixtures/update.zip', $testArchive);

        $existEntity = new PipelineTestMockEntity();
        $existEntity->setTestId(555);
        $existEntity->setTestName('to insert');
        $existEntity->setStartdate(new \DateTimeImmutable('2019-11-11 11:11:11'));
        $existEntity->setUuid(Uuid::fromString('123e4567-e89b-12d3-a456-426655440005'));

        $deletedEntity = new PipelineTestMockEntity();
        $deletedEntity->setTestId(444);

        $version = 321;
        $versionFullUrl = 'https://test.test/full';
        $versionDeltaUrl = 'https://test.test/delta';

        $versionEntity = new VersionManagerTestMockEntity();
        $versionEntity->setVersion($version);
        $versionEntity->setFullurl($versionFullUrl);
        $versionEntity->setDeltaurl($versionDeltaUrl);

        $state = new ArrayState(
            [
                StateParameter::PATH_TO_DOWNLOAD_FILE->value => $testArchive,
                StateParameter::PATH_TO_EXTRACT_FOLDER->value => $testDir,
                StateParameter::FIAS_NEXT_VERSION_NUMBER->value => $version,
                StateParameter::FIAS_NEXT_VERSION_FULL_URL->value => $versionFullUrl,
                StateParameter::FIAS_NEXT_VERSION_DELTA_URL->value => $versionDeltaUrl,
            ]
        );

        $pipeline = $this->createPipeLine();
        $pipeline->run($state);

        $this->assertFileDoesNotExist($testArchive);
        $this->assertDoctrineHasEntity($existEntity);
        $this->assertDoctrineHasEntity($versionEntity);
        $this->assertDoctrineHasNotEntity($deletedEntity);
    }

    /**
     * Cоздает объект пайплайна для тестов.
     */
    private function createPipeLine(): Pipe
    {
        $fiasEntityRegistry = new ArrayEntityRegistry(
            [
                new BaseEntityDescriptor(
                    [
                        'name' => 'mock',
                        'xmlPath' => '/mockList/mock',
                        'insertFileMask' => 'AS_MOCK_*.XML',
                        'deleteFileMask' => 'AS_DEL_MOCK_*.XML',
                        'fields' => [
                            new BaseEntityField(
                                [
                                    'name' => 'testId',
                                    'type' => 'int',
                                    'isPrimary' => true,
                                ]
                            ),
                            new BaseEntityField(
                                [
                                    'name' => 'testName',
                                    'type' => 'string',
                                ]
                            ),
                            new BaseEntityField(
                                [
                                    'name' => 'startdate',
                                    'type' => 'string',
                                    'subType' => 'date',
                                ]
                            ),
                            new BaseEntityField(
                                [
                                    'name' => 'uuid',
                                    'type' => 'string',
                                    'subType' => 'uuid',
                                ]
                            ),
                        ],
                    ]
                ),
            ]
        );

        $fiasEntityManager = new BaseEntityManager(
            $fiasEntityRegistry,
            [
                'mock' => PipelineTestMockEntity::class,
            ]
        );

        $storage = new BulkInsertDoctrineStorage($this->getEntityManager());

        $xmlReader = new BaseXmlReader();

        $serializer = new FiasSerializer();

        $versionManager = new DoctrineVersionManager(
            $this->getEntityManager(),
            VersionManagerTestMockEntity::class
        );

        $tasks = [
            new UnpackTask(new ZipUnpacker()),
            new SelectFilesToProceedTask($fiasEntityManager),
            new DataUpsertTask($fiasEntityManager, $xmlReader, $storage, $serializer),
            new DataDeleteTask($fiasEntityManager, $xmlReader, $storage, $serializer),
            new VersionSetTask($versionManager),
        ];

        return new ArrayPipe($tasks, new CleanupTask());
    }
}
