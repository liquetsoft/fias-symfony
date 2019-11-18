<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Класс с описанием настроек бандла.
 */
class Configuration implements ConfigurationInterface
{
    const CONFIG_NAME = 'liquetsoft_fias';

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress UndefinedMethod
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::CONFIG_NAME);

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('informer_wsdl')->defaultValue('http://fias.nalog.ru/WebServices/Public/DownloadService.asmx?WSDL')->end()
                ->scalarNode('registry_yaml')->defaultValue('%kernel.root_dir%/../vendor/liquetsoft/fias-component/resources/fias_entities.yaml')->end()
                ->scalarNode('temp_dir')->defaultValue('%kernel.cache_dir%/fias')->end()
                ->scalarNode('version_manager_entity')->defaultValue('')->end()
                ->scalarNode('insert_batch_count')->defaultValue(800)->end()
                ->scalarNode('path_to_console_bin')->defaultValue('%kernel.root_dir%/../bin/console')->end()
                ->scalarNode('number_of_parallel')->defaultValue(6)->end()
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
}
