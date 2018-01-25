<?php

namespace VaderLab\SecurityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /*
     *     vaderlab.security.user_provider.class: 'VaderLab\SecurityBundle\Service\User\Provider\UserProvider'
    vaderlab.api.http_client.class: 'VaderLab\SecurityBundle\Http\Client'
    vaderlab.security_users_information_get: '/api/user/current.json'
    vaderlab.security.authentificator.class: 'VaderLab\SecurityBundle\Security\Authentificator'
    vaderlab.security.cache_ttl: 300

     */
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('vaderlab');

        $rootNode
            ->children()
                ->arrayNode('security')
                    ->children()
                        ->arrayNode('api')
                            ->children()
                                ->scalarNode('cache_provider')
                                    ->defaultValue('vaderlab.security.cache')
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('api_key')
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('timeout')
                                    ->defaultValue(2)
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('url')
                                    ->defaultValue('https://www.vaderlab.com/api')
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                    ->end()
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
