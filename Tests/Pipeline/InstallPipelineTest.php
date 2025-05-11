<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use Liquetsoft\Fias\Component\EntityDescriptor\BaseEntityDescriptor;
use Liquetsoft\Fias\Component\EntityField\BaseEntityField;
use Liquetsoft\Fias\Component\EntityManager\BaseEntityManager;
use Liquetsoft\Fias\Component\EntityRegistry\ArrayEntityRegistry;
use Liquetsoft\Fias\Component\FiasFileSelector\FiasFileSelectorArchive;
use Liquetsoft\Fias\Component\FilesDispatcher\FilesDispatcherImpl;
use Liquetsoft\Fias\Component\Pipeline\Pipe\ArrayPipe;
use Liquetsoft\Fias\Component\Pipeline\Pipe\Pipe;
use Liquetsoft\Fias\Component\Pipeline\State\ArrayState;
use Liquetsoft\Fias\Component\Pipeline\State\State;
use Liquetsoft\Fias\Component\Pipeline\State\StateParameter;
use Liquetsoft\Fias\Component\Pipeline\Task\ApplyNestedPipelineToFileTask;
use Liquetsoft\Fias\Component\Pipeline\Task\CleanupFilesUnpacked;
use Liquetsoft\Fias\Component\Pipeline\Task\CleanupTask;
use Liquetsoft\Fias\Component\Pipeline\Task\DataDeleteTask;
use Liquetsoft\Fias\Component\Pipeline\Task\DataInsertTask;
use Liquetsoft\Fias\Component\Pipeline\Task\SelectFilesToProceedTask;
use Liquetsoft\Fias\Component\Pipeline\Task\Task;
use Liquetsoft\Fias\Component\Pipeline\Task\TruncateTask;
use Liquetsoft\Fias\Component\Pipeline\Task\UnpackTask;
use Liquetsoft\Fias\Component\Pipeline\Task\VersionSetTask;
use Liquetsoft\Fias\Component\Unpacker\UnpackerZip;
use Liquetsoft\Fias\Component\XmlReader\BaseXmlReader;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer\FiasSerializer;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage\BulkInsertDoctrineStorage;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\DoctrineTestCase;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\PipelineTestMockEntity;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\VersionManagerTestMockEntity;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\VersionManager\DoctrineVersionManager;
use Marvin255\FileSystemHelper\FileSystemFactory;
use Symfony\Component\Uid\Uuid;

/**
 * Тест для объекта папйлайна для установки базы данных.
 *
 * @internal
 */
final class InstallPipelineTest extends DoctrineTestCase
{
    /**
     * Тест для проверки пайплайна с установкой ФИАС с ноля.
     */
    public function testInstall(): void
    {
        $testDir = $this->getPathToTestDir();
        $testArchive = "{$testDir}/install.zip";
        copy(__DIR__ . '/_fixtures/install.zip', $testArchive);

        $existEntity = new PipelineTestMockEntity();
        $existEntity->setTestId(321);
        $existEntity->setTestName('to insert');
        $existEntity->setStartdate(new \DateTimeImmutable('2019-11-11 11:11:11'));
        $existEntity->setUuid(Uuid::fromString('123e4567-e89b-12d3-a456-426655440001'));
        $existEntity->setStringCode('227010000010000016740025000000000');

        $deletedEntity = new PipelineTestMockEntity();
        $deletedEntity->setTestId(123);

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
                StateParameter::PATH_TO_SOURCE->value => $testArchive,
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
        $fs = FileSystemFactory::create();
        $storage = new BulkInsertDoctrineStorage($this->getEntityManager());
        $unpacker = new UnpackerZip();
        $filesSelector = new FiasFileSelectorArchive($unpacker, $fiasEntityManager);
        $filesDispatcher = new FilesDispatcherImpl($fiasEntityManager);
        $xmlReader = new BaseXmlReader();
        $serializer = new FiasSerializer();
        $versionManager = new DoctrineVersionManager(
            $this->getEntityManager(),
            VersionManagerTestMockEntity::class
        );

        $nestedTasksPipeline = new ArrayPipe(
            [
                new UnpackTask($unpacker),
                new DataInsertTask($fiasEntityManager, $xmlReader, $storage, $serializer),
                new DataDeleteTask($fiasEntityManager, $xmlReader, $storage, $serializer),
            ],
            new CleanupFilesUnpacked($fs),
        );

        $dispatchFilesTask = new class($filesDispatcher) implements Task {
            public function __construct(private readonly FilesDispatcherImpl $filesDispatcher)
            {
            }

            /**
             * @psalm-suppress MixedArgument
             */
            #[\Override]
            public function run(State $state): State
            {
                $dispatchedFiles = $this->filesDispatcher->dispatch($state->getParameter(StateParameter::FILES_TO_PROCEED), 1);

                return $state->setParameter(StateParameter::FILES_TO_PROCEED, $dispatchedFiles[0]);
            }
        };

        $tasks = [
            new TruncateTask($fiasEntityManager, $storage),
            new SelectFilesToProceedTask($filesSelector),
            $dispatchFilesTask,
            new ApplyNestedPipelineToFileTask($nestedTasksPipeline),
            new VersionSetTask($versionManager),
        ];

        return new ArrayPipe($tasks, new CleanupTask($fs));
    }
}
