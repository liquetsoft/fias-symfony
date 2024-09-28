<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Класс расширения для предоставления бандлом сервисов.
 */
final class LiquetsoftFiasExtension extends Extension
{
    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->loadConfigurationToContainer($configs, $container);

        $this->loadServicesToContainer($container, 'services');
    }

    /**
     * Регистрирует параметры конфигов бандла.
     *
     * @psalm-suppress MixedArgument
     */
    private function loadConfigurationToContainer(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        foreach ($config as $key => $value) {
            $container->setParameter(Configuration::CONFIG_NAME . '.' . $key, $value);
        }
    }

    /**
     * Регистрирует сервисы бандла.
     *
     * @throws \Exception
     */
    private function loadServicesToContainer(ContainerBuilder $container, string $servicesBundle): void
    {
        $configDir = \dirname(__DIR__) . '/Resources/config';
        $loader = new YamlFileLoader($container, new FileLocator($configDir));

        $loader->load("{$servicesBundle}.yaml");
    }
}
