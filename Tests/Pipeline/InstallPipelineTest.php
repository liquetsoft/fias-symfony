<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use DateTime;
use Liquetsoft\Fias\Component\EntityDescriptor\BaseEntityDescriptor;
use Liquetsoft\Fias\Component\EntityField\BaseEntityField;
use Liquetsoft\Fias\Component\EntityManager\BaseEntityManager;
use Liquetsoft\Fias\Component\EntityRegistry\ArrayEntityRegistry;
use Liquetsoft\Fias\Component\Pipeline\Pipe\ArrayPipe;
use Liquetsoft\Fias\Component\Pipeline\Pipe\Pipe;
use Liquetsoft\Fias\Component\Pipeline\State\ArrayState;
use Liquetsoft\Fias\Component\Pipeline\Task\CleanupTask;
use Liquetsoft\Fias\Component\Pipeline\Task\DataDeleteTask;
use Liquetsoft\Fias\Component\Pipeline\Task\DataInsertTask;
use Liquetsoft\Fias\Component\Pipeline\Task\SelectFilesToProceedTask;
use Liquetsoft\Fias\Component\Pipeline\Task\Task;
use Liquetsoft\Fias\Component\Pipeline\Task\TruncateTask;
use Liquetsoft\Fias\Component\Pipeline\Task\UnpackTask;
use Liquetsoft\Fias\Component\Unpacker\ZipUnpacker;
use Liquetsoft\Fias\Component\XmlReader\BaseXmlReader;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer\FiasSerializer;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage\BulkInsertDoctrineStorage;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\DoctrineTestCase;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\MockEntity;
use SplFileInfo;

/**
 * Тест для объекта папйлайна для установки базы данных.
 */
class InstallPipelineTest extends DoctrineTestCase
{
    /**
     * Тест для проверки пайплайна с установкой ФИАС с ноля.
     */
    public function testInstall()
    {
        $testDir = $this->getPathToTestDir();
        $testArchive = "{$testDir}/install.zip";

        copy(__DIR__ . '/_fixtures/install.zip', $testArchive);

        $existEntity = new MockEntity();
        $existEntity->setTestId(321);
        $existEntity->setTestName('to insert');
        $existEntity->setStartdate(new DateTime('2019-11-11 11:11:11'));

        $deletedEntity = new MockEntity();
        $deletedEntity->setTestId(123);
        $deletedEntity->setTestName('to remove');
        $deletedEntity->setStartdate(new DateTime('2019-10-10 10:10:10'));

        $state = new ArrayState();
        $state->setAndLockParameter(Task::DOWNLOAD_TO_FILE_PARAM, new SplFileInfo($testArchive));
        $state->setAndLockParameter(Task::EXTRACT_TO_FOLDER_PARAM, new SplFileInfo($testDir));

        $pipeline = $this->createPipeLine();
        $pipeline->run($state);

        $this->assertFileDoesNotExist($testArchive);
        $this->assertDoctrineHasEntity($existEntity);
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
                        ],
                    ]
                ),
            ]
        );

        $fiasEntityManager = new BaseEntityManager(
            $fiasEntityRegistry,
            [
                'mock' => MockEntity::class,
            ]
        );

        $storage = new BulkInsertDoctrineStorage($this->getEntityManager());

        $xmlReader = new BaseXmlReader();

        $serializer = new FiasSerializer();

        $tasks = [
            new UnpackTask(new ZipUnpacker()),
            new TruncateTask($fiasEntityManager, $storage),
            new SelectFilesToProceedTask($fiasEntityManager),
            new DataInsertTask($fiasEntityManager, $xmlReader, $storage, $serializer),
            new DataDeleteTask($fiasEntityManager, $xmlReader, $storage, $serializer),
        ];

        return new ArrayPipe($tasks, new CleanupTask());
    }
}
