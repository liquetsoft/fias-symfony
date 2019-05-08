<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\Pipeline\Pipe\Pipe;
use Liquetsoft\Fias\Component\Pipeline\State\ArrayState;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Команда, которая запускает установку ФИАС.
 */
class InstallCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'liquetsoft:fias:install';

    /**
     * @var Pipe
     */
    protected $pipeline;

    /**
     * @param Pipe $pipeline
     */
    public function __construct(Pipe $pipeline)
    {
        $this->pipeline = $pipeline;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setDescription('Installs full version of FIAS from scratch.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('Installing full version of FIAS.');

        $state = new ArrayState;
        $this->pipeline->run($state);

        $io->success('Full version of FIAS installed.');
    }
}
