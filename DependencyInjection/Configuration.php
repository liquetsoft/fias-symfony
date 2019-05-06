<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\FiasBundle\DependencyInjection;

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
                ->arrayNode('entity_bindings')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('source')->end()
                        ->end()
                    ->end()
                    ->defaultValue([
                        'ActualStatus' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\ActualStatus',
                        'AddressObject' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\AddressObject',
                        'AddressObjectType' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\AddressObjectType',
                        'CenterStatus' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\CenterStatus',
                        'CurrentStatus' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\CurrentStatus',
                        'EstateStatus' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\EstateStatus',
                        'FlatType' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\FlatType',
                        'House' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\House',
                        'HouseStateStatus' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\HouseStateStatus',
                        'IntervalStatus' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\IntervalStatus',
                        'NormativeDocument' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\NormativeDocument',
                        'NormativeDocumentType' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\NormativeDocumentType',
                        'OperationStatus' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\OperationStatus',
                        'Room' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\Room',
                        'RoomType' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\RoomType',
                        'Stead' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\Stead',
                        'StructureStatus' => 'Liquetsoft\\Fias\\Symfony\\FiasBundle\\FiasEntity\\StructureStatus',
                    ])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
