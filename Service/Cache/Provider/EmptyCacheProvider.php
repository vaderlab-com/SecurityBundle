<?php
/**
 * Created by PhpStorm.
 * User: kost
 * Date: 25.01.18
 * Time: 2:47
 */

namespace VaderLab\SecurityBundle\Service\Cache\Provider;


use VaderLab\SecurityBundle\Service\Cache\CacheProviderInterface;


class EmptyCacheProvider implements CacheProviderInterface
{
    public function get(string $key): ?string
    {
        return null;
    }

    public function set(string $key, $value): void
    {
    }

    public function exists(string $key): bool
    {
        return false;
    }
}