<?php
/**
 * Created by PhpStorm.
 * User: kost
 * Date: 26.01.18
 * Time: 2:05
 */

namespace VaderLab\SecurityBundle\Service\Cache;

use Symfony\Component\DependencyInjection\Container;
use VaderLab\SecurityBundle\Service\Cache\Provider\EmptyCacheProvider;

class CreateCacheProviderFactory
{
    private $container;
    private $serviceName;

    public function __construct(Container $container, string $serviceName)
    {
        $this->container = $container;
        $this->serviceName = $serviceName;
    }

    /**
     * @return object
     * @throws \Exception | InvalidCacheFactoryException
     */
    public function create()
    {
        if(!$this->serviceName) {
            return new EmptyCacheProvider();
        }

        $service = $this->container->get($this->serviceName);

        return $service;
    }
}