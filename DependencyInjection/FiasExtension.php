<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\FiasBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * Класс расширения для предоставления бандлом сервисов.
 */
class FiasExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->loadConfigurationToContainer($configs, $container);
        $this->loadServicesToContainer($container);
    }

    /**
     * Регистрирует параметры конфигов бандла.
     */
    protected function loadConfigurationToContainer(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);
        foreach ($config as $key => $value) {
            $container->setParameter(Configuration::CONFIG_NAME . '.' . $key, $value);
        }
    }

    /**
     * Регистрирует сервисы бандла.
     */
    protected function loadServicesToContainer(ContainerBuilder $container): void
    {
        $configDir = dirname(__DIR__) . '/Resources/config';
        $loader = new YamlFileLoader($container, new FileLocator($configDir));

        $loader->load('services.yaml');
    }
}
