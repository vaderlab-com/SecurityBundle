<?php
/**
 * Created by PhpStorm.
 * User: kost
 * Date: 12.10.17
 * Time: 21:18
 */

namespace VaderLab\SecurityBundle\Service\User\Provider;


use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use VaderLab\SecurityBundle\Http\ApiHttpClientInterface;
use VaderLab\SecurityBundle\Model\User;
use VaderLab\SecurityBundle\Service\Cache\CacheProviderInterface;
use VaderLab\SecurityBundle\Service\Cache\Provider\EmptyCacheProvider;

class UserProvider implements UserProviderInterface
{
    /**
     * @var string
     */
    private $userinfoUrl;

    /**
     * @var ApiHttpClientInterface
     */
    private $httpClient;

    /**
     * @var CacheProviderInterface
     */
    private $cache;

    /**
     * UserProvider constructor.
     * @param ApiHttpClientInterface $httpClient
     * @param string $userInformationUrl
     * @param CacheProviderInterface $cache
     */
    public function __construct(
        ApiHttpClientInterface $httpClient,
        string $userInformationUrl,
        $cache = null
    ) {
        $this->httpClient = $httpClient;
        $this->userinfoUrl = $userInformationUrl;
        $this->cache = $cache;
    }

    /**
     * @param string $username
     */
    public function loadUserByUsername($username)
    {
        throw new \LogicException('Method is not supported');
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    /**
     * @return UserInterface
     * @throws EntityNotFoundException
     * @throws InternalErrorException
     */
    public function loadCurrentUser(): ?UserInterface
    {
        $data = $this->_getUserInformation();
        if(!$data) {
            return null;
        }

        return new User($data);
    }

    /**
     * @param string $bearer
     * @return array|null
     * @throws EntityNotFoundException
     * @throws InternalErrorException
     */
    protected function _getUserInformation(): ?array
    {
        $bearer = $this->httpClient->getBearer();

        if(!$bearer) {
            return null;
        }

        $data = $this->cache->get($bearer);
        if($data) {
            return json_decode($data, true);
        }

        if(!$this->cache->exists($bearer)) {
            try {
                $data = $this->httpClient->get($this->userinfoUrl);
            } catch (\Exception $exception) {
                return null;
            }
        }

        if(!in_array($data->getStatusCode(), [200, 301])) {
            throw new EntityNotFoundException('User with token ' . $bearer . ' no exists');
        }

        $content = $data->getBody()->getContents();
        $data = json_decode($content, true);
        if(!$data) {
            throw new InternalErrorException('Content is broken ', $content);
        }

        $this->cache->set($bearer, $content);

        return $data;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class;
    }
}