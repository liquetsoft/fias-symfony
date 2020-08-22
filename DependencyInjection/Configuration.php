<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Класс с описанием настроек бандла.
 */
class Configuration implements ConfigurationInterface
{
    public const CONFIG_NAME = 'liquetsoft_fias';

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress UndefinedMethod
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $rootDir = $this->getPathToRootDir();

        $treeBuilder = new TreeBuilder(self::CONFIG_NAME);
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('informer_wsdl')->defaultValue('http://fias.nalog.ru/WebServices/Public/DownloadService.asmx?WSDL')->end()
                ->scalarNode('registry_path')->defaultValue(null)->end()
                ->scalarNode('temp_dir')->defaultValue('%kernel.cache_dir%/fias')->end()
                ->scalarNode('version_manager_entity')->defaultValue('')->end()
                ->scalarNode('insert_batch_count')->defaultValue(800)->end()
                ->scalarNode('path_to_console_bin')->defaultValue("{$rootDir}/bin/console")->end()
                ->scalarNode('number_of_parallel')->defaultValue(5)->end()
                ->scalarNode('paralleling_running_command')->defaultValue('liquetsoft:fias:install_parallel_running')->end()
                ->arrayNode('entity_bindings')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                    ->defaultValue([])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Returns path to project root dir.
     *
     * @return string
     */
    private function getPathToRootDir()
    {
        return version_compare(Kernel::VERSION, '5.0.0', '<')
            ? '%kernel.root_dir%/..'
            : '%kernel.project_dir%';
    }
}
