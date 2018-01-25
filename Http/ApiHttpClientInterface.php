<?php
/**
 * Created by PhpStorm.
 * User: kost
 * Date: 31.10.17
 * Time: 21:06
 */

namespace VaderLab\SecurityBundle\Http;

use GuzzleHttp\Promise;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Interface ApiHttpClientInterface
 * @package VaderLab\SecurityBundle\Http
 *
 * @method ResponseInterface get(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface head(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface put(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface post(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface patch(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface delete(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface getAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface headAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface putAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface postAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface patchAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface deleteAsync(string|UriInterface $uri, array $options = [])
 */
interface ApiHttpClientInterface extends ClientInterface
{
    /**
     * @param string $bearer
     * @return ApiHttpClientInterface
     */
    public function setBearer(?string $bearer): ApiHttpClientInterface;

    /**
     * @return null|string
     */
    public function getBearer(): ?string;

    /**
     * @param string $apiKey
     * @return ApiHttpClientInterface
     */
    public function setApiKey(?string $apiKey): ApiHttpClientInterface;

    /**
     * @return string
     */
    public function getApiKey(): ?string;
}