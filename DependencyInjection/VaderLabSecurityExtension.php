<?php

namespace VaderLab\SecurityBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class VaderLabSecurityExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');


        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $secApi = $config['security']['api'];

        $container->setParameter('vaderlab.security.api.api_key', $secApi['api_key']);
        $container->setParameter('vaderlab.security.api.url', $secApi['url']);
        $container->setParameter('vaderlab.security.api.timeout', $secApi['timeout']);
        $container->setParameter('vaderlab.security.api.cache_provider', $secApi['cache_provider']);
    }

    public function getAlias()
    {
        return 'vaderlab';
    }
}
