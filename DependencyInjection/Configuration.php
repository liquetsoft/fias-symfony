<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Класс с описанием настроек бандла.
 *
 * @internal
 */
final class Configuration implements ConfigurationInterface
{
    public const CONFIG_NAME = 'liquetsoft_fias';

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress UndefinedMethod
     * @psalm-suppress MixedMethodCall
     */
    #[\Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $rootDir = $this->getPathToRootDir();

        $treeBuilder = new TreeBuilder(self::CONFIG_NAME);
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('registry_path')
                    ->defaultValue(null)
                ->end()
                ->scalarNode('temp_dir')
                    ->defaultValue('%kernel.cache_dir%/fias')
                ->end()
                ->scalarNode('version_manager_entity')
                    ->defaultValue('')
                ->end()
                ->scalarNode('insert_batch_count')
                    ->defaultValue(800)
                ->end()
                ->scalarNode('path_to_console_bin')
                    ->defaultValue("{$rootDir}/bin/console")
                ->end()
                ->scalarNode('number_of_parallel')
                    ->defaultValue(10)
                ->end()
                ->scalarNode('instal_running_command')
                    ->defaultValue('liquetsoft:fias:install_parallel_running')
                ->end()
                ->scalarNode('update_running_command')
                    ->defaultValue('liquetsoft:fias:update_parallel_running')
                ->end()
                ->arrayNode('entity_bindings')
                    ->useAttributeAsKey('name')
                        ->prototype('scalar')
                    ->end()
                    ->defaultValue([])
                ->end()
                ->scalarNode('download_retry_attempts')
                    ->defaultValue(10)
                ->end()
                ->arrayNode('files_filter')
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
        return '%kernel.project_dir%';
    }
}
