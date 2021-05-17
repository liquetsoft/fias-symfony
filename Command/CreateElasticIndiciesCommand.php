<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\EntityManager\EntityManager;
use Liquetsoft\Fias\Elastic\Exception\IndexBuilderException;
use Liquetsoft\Fias\Elastic\IndexBuilder\IndexBuilder;
use Liquetsoft\Fias\Elastic\IndexMapperRegistry\IndexMapperRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Команда, которая создает все нужные индексы в elasticsearch по описанию
 * из fias-elastic.
 */
class CreateElasticIndiciesCommand extends Command
{
    /**
     * @var string|null
     */
    protected static $defaultName = 'liquetsoft:fias:create_elastic_indicies';

    private EntityManager $entityManager;

    private IndexMapperRegistry $registry;

    private IndexBuilder $builder;

    public function __construct(EntityManager $entityManager, IndexMapperRegistry $registry, IndexBuilder $builder)
    {
        $this->entityManager = $entityManager;
        $this->registry = $registry;
        $this->builder = $builder;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates indices in elasticsearch or update mapping if index exists.')
            ->addOption(
                'replace',
                null,
                InputOption::VALUE_OPTIONAL,
                'Should command removes index if it already exists?',
                false
            )
        ;
    }

    /**
     * Запускает команду на исполнение.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws IndexBuilderException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('Creating indices in elasticsearch...');

        $shouldReplace = $input->getOption('replace');
        $shouldReplace = $shouldReplace !== false;

        $bindedClasses = $this->entityManager->getBindedClasses();
        foreach ($bindedClasses as $bindedClass) {
            if (!$this->registry->hasMapperForKey($bindedClass)) {
                continue;
            }
            $mapper = $this->registry->getMapperForKey($bindedClass);
            if ($shouldReplace && $this->builder->hasIndex($mapper)) {
                $this->builder->delete($mapper);
                $io->note("Index '{$mapper->getName()}' already exists. Removing...");
            }
            $this->builder->save($mapper);
            $io->success("Index '{$mapper->getName()}' saved.");
        }

        return Command::SUCCESS;
    }
}
