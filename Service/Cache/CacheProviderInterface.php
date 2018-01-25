<?php
/**
 * Created by PhpStorm.
 * User: kost
 * Date: 25.01.18
 * Time: 2:44
 */

namespace VaderLab\SecurityBundle\Service\Cache;


interface CacheProviderInterface
{
    public function get(string $key): ?string;

    public function set(string $key, $value): void;

    public function exists(string $key): bool;
}