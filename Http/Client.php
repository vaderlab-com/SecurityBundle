<?php
/**
 * Created by PhpStorm.
 * User: kost
 * Date: 31.10.17
 * Time: 20:56
 */

namespace VaderLab\SecurityBundle\Http;

use GuzzleHttp\Client as BaseClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Promise;

/**
 * Class Client
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
class Client implements ApiHttpClientInterface
{
    /**
     * @var BaseClient
     */
    private $client;

    /**
     * @var string
     */
    private $bearer;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * Client constructor.
     * @param BaseClient $client
     * @param string $apiKey
     */
    public function __construct(
        string $apiUrl,
        int $timeout = 2,
        ?string $apiKey = null
    ) {
        $this->apiKey = $apiKey;
        $this->initClient($apiUrl, $timeout);
    }


    protected function initClient(string $url, int $timeout)
    {
        $this->client = new BaseClient([
            'base_uri' => $url,
            'timeout'  => $timeout,
        ]);
    }

    /**
     * @param $method
     * @param $args
     * @return PromiseInterface|mixed|ResponseInterface
     */
    public function __call($method, $args)
    {
        if (count($args) < 1) {
            throw new \InvalidArgumentException('Magic request methods require a URI and optional options array');
        }

        $uri = $args[0];
        $opts = isset($args[1]) ? $args[1] : [];

        return substr($method, -5) === 'Async'
            ? $this->requestAsync(substr($method, 0, -5), $uri, $opts)
            : $this->request($method, $uri, $opts);
    }

    /**
     * @param string $bearer
     * @return ApiHttpClientInterface
     */
    public function setBearer(?string $bearer): ApiHttpClientInterface
    {
        $this->bearer = $bearer;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getBearer(): ?string
    {
        return $this->bearer;
    }

    /**
     * @param string $apiKey
     * @return ApiHttpClientInterface
     */
    public function setApiKey(?string $apiKey): ApiHttpClientInterface
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * Send an HTTP request.
     *
     * @param RequestInterface $request Request to send
     * @param array $options Request options to apply to the given
     *                                  request and to the transfer.
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function send(RequestInterface $request, array $options = [])
    {
        return $this->client->send($request, $this->configureOptions($options));
    }

    /**
     * Asynchronously send an HTTP request.
     *
     * @param RequestInterface $request Request to send
     * @param array $options Request options to apply to the given
     *                                  request and to the transfer.
     *
     * @return PromiseInterface
     */
    public function sendAsync(RequestInterface $request, array $options = [])
    {
        return $this->client->sendAsync($request, $this->configureOptions($options));
    }

    /**
     * Create and send an HTTP request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param string $method HTTP method.
     * @param string|UriInterface $uri URI object or string.
     * @param array $options Request options to apply.
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function request($method, $uri, array $options = [])
    {
        return $this->client->request($method, $uri, $this->configureOptions($options));
    }

    /**
     * Create and send an asynchronous HTTP request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param string $method HTTP method
     * @param string|UriInterface $uri URI object or string.
     * @param array $options Request options to apply.
     *
     * @return PromiseInterface
     */
    public function requestAsync($method, $uri, array $options = [])
    {
        return $this->client->requestAsync($method, $uri, $options);
    }

    /**
     * Get a client configuration option.
     *
     * These options include default request options of the client, a "handler"
     * (if utilized by the concrete client), and a "base_uri" if utilized by
     * the concrete client.
     *
     * @param string|null $option The config option to retrieve.
     *
     * @return mixed
     */
    public function getConfig($option = null)
    {
        return $this->client->getConfig($option);
    }

    /**
     * @param array $options
     * @return array
     */
    protected function configureOptions(array $options = [])
    {
        $apiKey = $this->getApiKey();
        $bearer = $this->getBearer();

        if(!isset($options['headers'])) {
            $options['headers'] = [];
        }

        if($apiKey) {
            $options['headers']['api_key'] = $apiKey;
        }

        if($bearer) {
            $options['headers']['Authorization'] = $bearer;
        }

        return $options;
    }
}